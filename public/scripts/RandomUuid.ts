import {Notification} from "./Notification.js";

class RandomUuid
{
    public static readonly HostDomClass = "random-uuid";
    private static readonly UpperCaseDomClass = "uuid-upper";
    private static readonly RefreshDomClass = "uuid-refresh";
    private static readonly CopyDomClass = "uuid-copy";
    private static readonly DisplayDomClass = "uuid-display";

    private readonly m_host: HTMLElement;
    private readonly m_upperCase: HTMLInputElement;
    private readonly m_copy: HTMLButtonElement;
    private readonly m_refresh: HTMLButtonElement;
    private readonly m_display: HTMLElement;

    public constructor(host: HTMLElement)
    {
        this.m_host = host;
        this.m_upperCase = host.querySelector(`input.${RandomUuid.UpperCaseDomClass}`);
        this.m_refresh = host.querySelector(`button.${RandomUuid.RefreshDomClass}`);
        this.m_copy = host.querySelector(`button.${RandomUuid.CopyDomClass}`);
        this.m_display = host.querySelector(`.${RandomUuid.DisplayDomClass}`);

        if ("" === this.uuid) {
            this.uuid = crypto.randomUUID();
        }

        this.bindComponents();
    }

    public get hostElement(): HTMLElement
    {
        return this.m_host;
    }

    public get displayElement(): HTMLElement
    {
        return this.m_display;
    }

    public get refreshButton(): HTMLButtonElement
    {
        return this.m_refresh;
    }

    public get upperCaseCheckbox(): HTMLInputElement
    {
        return this.m_upperCase;
    }

    public get uuid(): string
    {
        return this.displayElement.innerText;
    }

    private set uuid(uuid: string)
    {
        this.showUuid(uuid);
    }

    public get upperCase(): boolean
    {
        return this.upperCaseCheckbox.checked;
    }

    public set upperCase(upper: boolean)
    {
        this.upperCaseCheckbox.checked = upper;
        // force a redisplay
        this.uuid = this.uuid;
    }

    protected showUuid(uuid: string): void
    {
        while (this.displayElement.firstChild) {
            this.displayElement.firstChild.remove();
        }

        this.displayElement.append(document.createTextNode(this.upperCase ? uuid.toUpperCase() : uuid.toLowerCase()));
    }

    private bindComponents(): void
    {
        this.m_upperCase.addEventListener("click", (event: MouseEvent) => this.onUpperCaseClicked(event));
        this.m_refresh.addEventListener("click", (event: MouseEvent) => this.onRefreshClicked(event));
        this.m_copy.addEventListener("click", (event: MouseEvent) => this.onCopyClicked(event));
    }

    protected onUpperCaseClicked(event: MouseEvent): void
    {
        // refresh the hash display
        this.showUuid(this.uuid);
    }

    protected onCopyClicked(event: MouseEvent): void
    {
        navigator.clipboard.writeText(this.m_display.innerText)
            .then(() => Notification.information(`The UUID has been copied to the clipboard.`));
    }

    protected onRefreshClicked(event: MouseEvent): void
    {
        this.uuid = crypto.randomUUID();
    }

    public static bootstrap(): void
    {
        document.querySelectorAll(`.${RandomUuid.HostDomClass}`).forEach((host: HTMLElement) => new RandomUuid(host));
    }
}

(function () {
    window.addEventListener("load", RandomUuid.bootstrap);
})();
