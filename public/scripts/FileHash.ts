import {Notification} from "./Notification.js";
import {ApiResponse} from "./ApiResponse.js";

class FileHash
{
    /**
     * How long after the user stops typing to wait for the next key before fetching the hash.
     *
     * If the user presses a key within this time the hash is not fetched, to avoid spamming the backend with requests.
     */
    protected static readonly RefreshTimerDuration = 350;

    public static readonly HostDomClass = "file-hash";
    private static readonly FileDomClass = "hash-file";
    private static readonly UploadDomClass = "hash-generate";
    private static readonly UpperCaseDomClass = "hash-upper";
    private static readonly CopyDomClass = "hash-copy";
    private static readonly DisplayDomClass = "hash-display";

    private readonly m_host: HTMLElement;
    private readonly m_csrf: string;
    private readonly m_algorithm: string;
    private readonly m_file: HTMLInputElement;
    private readonly m_upload: HTMLButtonElement;
    private readonly m_upperCase: HTMLInputElement;
    private readonly m_copy: HTMLButtonElement;
    private readonly m_display: HTMLElement;

    public constructor(host: HTMLElement)
    {
        this.m_host = host;
        this.m_algorithm = host.dataset.algorithm;
        this.m_csrf = host.dataset.csrf;
        this.m_file = host.querySelector(`input.${FileHash.FileDomClass}`);
        this.m_upload = host.querySelector(`button.${FileHash.UploadDomClass}`);
        this.m_upperCase = host.querySelector(`input.${FileHash.UpperCaseDomClass}`);
        this.m_copy = host.querySelector(`button.${FileHash.CopyDomClass}`);
        this.m_display = host.querySelector(`.${FileHash.DisplayDomClass}`);
        this.bindEvents();

        if (!this.hasFile) {
            this.m_copy.disabled = true;
        }
    }

    protected get endpoint(): string
    {
        return `/api/hashes/${this.m_algorithm}/file/hash`;
    }

    public get algorithm(): string
    {
        return this.m_algorithm;
    }

    public get hasFile(): boolean
    {
        return 0 < this.m_file.files.length;
    }

    public get fileElement(): HTMLInputElement
    {
        return this.m_file;
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
        this.m_upload.addEventListener("click", (event: MouseEvent) => this.onUploadClicked(event));
        this.m_upperCase.addEventListener("click", (event: MouseEvent) => this.onUpperCaseClicked(event));
        this.m_copy.addEventListener("click", (event: MouseEvent) => this.onCopyClicked(event));
    }

    protected fetchHash(): void
    {
        if (!this.hasFile) {
            this.m_copy.disabled = true;
            this.showHash("<no hash>");
            return;
        }

        this.m_copy.disabled = false;
        const body = new FormData();
        body.set("file", this.fileElement.files[0]);

        fetch(this.endpoint, {
            method: "POST",
            body: body,
            headers: {
                "x-csrf-token": this.m_csrf,
            },
        })
            .then((response: Response) => response.json())
            .then((json) => this.onResponseReceived(json))
            .catch(() => this.onRequestFailed(`Request for ${this.algorithm} hash of file failed.`));
    }

    protected onUploadClicked(event: MouseEvent): void
    {
        this.fetchHash();
    }

    protected onRequestFailed(message?: string): void
    {
        Notification.error(message ?? `Request for ${this.algorithm} file hash failed.`);
    }

    protected onResponseReceived(response: ApiResponse): void
    {
        if (ApiResponse.Ok === response.result.code) {
            this.showHash(response.payload);
        } else {
            Notification.error(response.result.message);
        }
    }

    protected onUpperCaseClicked(event: MouseEvent): void
    {
        if ("<no-hash>" === this.hash) {
            return;
        }

        // refresh the hash display
        this.showHash(this.hash);
    }

    protected onCopyClicked(event: MouseEvent): void
    {
        navigator.clipboard.writeText(this.m_display.innerText)
            .then(() => Notification.information(`The ${this.algorithm} hash has been copied to the clipboard.`));
    }

    public static bootstrap(): void
    {
        document.querySelectorAll(`.${FileHash.HostDomClass}`).forEach((host: HTMLElement) => new FileHash(host));
    }
}

(function ()
{
    window.addEventListener("load", FileHash.bootstrap);
})();
