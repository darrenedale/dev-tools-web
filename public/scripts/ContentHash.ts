import {Notification} from "./Notification.js";

class ContentHash
{
    /**
     * How long after the user stops typing to wait for the next key before fetching the hash.
     *
     * If the user presses a key within this time the hash is not fetched, to avoid spamming the backend with requests.
     */
    protected static readonly RefreshTimerDuration = 350;

    public static readonly HostDomClass = "content-hash";
    private static readonly ContentDomClass = "hash-source";
    private static readonly UpperCaseDomClass = "hash-upper";
    private static readonly CopyDomClass = "hash-copy";
    private static readonly DisplayDomClass = "hash-display";

    private readonly m_host: HTMLElement;
    private readonly m_csrf: string;
    private readonly m_algorithm: string;
    private readonly m_source: HTMLTextAreaElement;
    private readonly m_upperCase: HTMLInputElement;
    private readonly m_copy: HTMLButtonElement;
    private readonly m_display: HTMLElement;
    private m_refreshTimerId: number;

    public constructor(host: HTMLElement)
    {
        this.m_refreshTimerId = 0;
        this.m_host = host;
        this.m_algorithm = host.dataset.algorithm;
        this.m_csrf = host.dataset.csrf;
        this.m_source = host.querySelector(`textarea.${ContentHash.ContentDomClass}`);
        this.m_upperCase = host.querySelector(`input.${ContentHash.UpperCaseDomClass}`);
        this.m_copy = host.querySelector(`button.${ContentHash.CopyDomClass}`);
        this.m_display = host.querySelector(`.${ContentHash.DisplayDomClass}`);
        this.bindEvents();

        if ("" === this.sourceContent) {
            this.m_copy.disabled = true;
        }
    }

    protected get endpoint(): string
    {
        return `/api/hashes/${this.m_algorithm}/hash`;
    }

    public get algorithm(): string
    {
        return this.m_algorithm;
    }

    public get sourceContent(): string
    {
        return this.m_source.value;
    }

    public set sourceContent(content: string)
    {
        this.m_source.value = content;
    }

    public get sourceElement(): HTMLTextAreaElement
    {
        return this.m_source;
    }

    public get hostElement(): HTMLElement
    {
        return this.m_host;
    }

    public get displayElement(): HTMLElement
    {
        return this.m_display;
    }

    public get upperCaseCheckbox(): HTMLInputElement
    {
        return this.m_upperCase;
    }

    public get hash(): string
    {
        return this.displayElement.innerText;
    }

    public get upperCase(): boolean
    {
        return this.upperCaseCheckbox.checked;
    }

    public set upperCase(upper: boolean)
    {
        this.upperCaseCheckbox.checked = upper;
        // force a redisplay
        this.showHash(this.hash);
    }

    protected showHash(hash: string): void
    {
        while (this.displayElement.firstChild) {
            this.displayElement.firstChild.remove();
        }

        this.displayElement.append(document.createTextNode(this.upperCase ? hash.toUpperCase() : hash.toLowerCase()));
    }

    private bindEvents(): void
    {
        this.m_source.addEventListener("keyup", (event: KeyboardEvent) => this.onContentKeyPress(event));
        this.m_upperCase.addEventListener("click", (event: MouseEvent) => this.onUpperCaseClicked(event));
        this.m_copy.addEventListener("click", (event: MouseEvent) => this.onCopyClicked(event));
    }

    protected fetchHash(): void
    {
        if ("" === this.sourceContent) {
            this.m_copy.disabled = true;
            this.showHash("<no hash>");
            return;
        }

        this.m_copy.disabled = false;
        const body = new FormData();
        body.set("_token", this.m_csrf);
        body.set("content", this.sourceContent);

        fetch(this.endpoint, {
            method: "POST",
            body: body
        })
            .then((response: Response) => response.json())
            .then((json) => this.onHashReceived(json.payload));
    }

    protected onContentTimerTimeout(): void
    {
        this.fetchHash();
    }

    protected onContentKeyPress(event): void
    {
        if (0 !== this.m_refreshTimerId) {
            window.clearTimeout(this.m_refreshTimerId);
        }

        this.m_refreshTimerId = window.setTimeout(() => this.onContentTimerTimeout(), ContentHash.RefreshTimerDuration);
    }

    protected onHashReceived(hash: string): void
    {
        this.showHash(hash);
    }

    protected onUpperCaseClicked(event: MouseEvent): void
    {
        if ("" === this.sourceContent) {
            return;
        }

        // refresh the has display
        this.showHash(this.hash);
    }

    protected onCopyClicked(event: MouseEvent): void
    {
        navigator.clipboard.writeText(this.m_display.innerText)
            .then(() => Notification.information(`The ${this.algorithm} hash has been copied to the clipboard.`));
    }

    public static bootstrap(): void
    {
        document.querySelectorAll(`.${ContentHash.HostDomClass}`).forEach((host: HTMLElement) => new ContentHash(host));
    }
}

(function ()
{
    window.addEventListener("load", ContentHash.bootstrap);
})();
