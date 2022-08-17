import { Notification } from "./Notification.js";

class RandomHash
{
    public static readonly HostDomClass = "random-hash";
    private static readonly UpperCaseDomClass = "random-hash-upper";
    private static readonly RefreshDomClass = "random-hash-refresh";
    private static readonly CopyDomClass = "random-hash-copy";
    private static readonly DisplayDomClass = "random-hash-display";

    private readonly m_host: HTMLElement;
    private readonly m_algorithm: string;
    private readonly m_upperCase: HTMLInputElement;
    private readonly m_copy: HTMLButtonElement;
    private readonly m_refresh: HTMLButtonElement;
    private readonly m_display: HTMLElement;

    public constructor(host: HTMLElement) {
        this.m_host = host;
        this.m_algorithm = host.dataset.algorithm;
        this.m_upperCase = host.querySelector(`input.${RandomHash.UpperCaseDomClass}`);
        this.m_refresh = host.querySelector(`button.${RandomHash.RefreshDomClass}`);
        this.m_copy = host.querySelector(`button.${RandomHash.CopyDomClass}`);
        this.m_display = host.querySelector(`.${RandomHash.DisplayDomClass}`);
        this.bindEvents();
    }

    protected get endpoint(): string {
        return `/api/hashes/${this.m_algorithm}/random`;
    }

    public get algorithm(): string {
        return this.m_algorithm;
    }

    public get hostElement(): HTMLElement {
        return this.m_host;
    }

    public get displayElement(): HTMLElement {
        return this.m_display;
    }

    public get refreshButton(): HTMLButtonElement {
        return this.m_refresh;
    }

    public get upperCaseCheckbox(): HTMLInputElement {
        return this.m_upperCase;
    }

    public get hash(): string {
        return this.displayElement.innerText;
    }

    public set hash(hash: string) {
        this.showHash(hash);
    }

    public get upperCase(): boolean {
        return this.upperCaseCheckbox.checked;
    }

    public set upperCase(upper: boolean) {
        this.upperCaseCheckbox.checked = upper;
        // force a redisplay
        this.hash = this.hash;
    }

    protected showHash(hash: string): void {
        while (this.displayElement.firstChild) {
            this.displayElement.firstChild.remove();
        }

        this.displayElement.append(document.createTextNode(this.upperCase ? hash.toUpperCase() : hash.toLowerCase()));
    }

    private bindEvents(): void {
        this.m_upperCase.addEventListener("click", (event: MouseEvent) => this.onUpperCaseClicked(event));
        this.m_refresh.addEventListener("click", (event: MouseEvent) => this.onRefreshClicked(event));
        this.m_copy.addEventListener("click", (event: MouseEvent) => this.onCopyClicked(event));
    }

    protected onHashReceived(hash: string): void {
        this.hash = hash;
    }

    protected onUpperCaseClicked(event: MouseEvent): void {
        // refresh the has display
        this.showHash(this.hash);
    }

    protected onCopyClicked(event: MouseEvent): void {
        navigator.clipboard.writeText(this.m_display.innerText)
            .then(() => Notification.information(`${this.algorithm} hash '${this.hash}' copied to clipboard.`));
    }

    protected onRefreshClicked(event: MouseEvent): void {
        fetch(this.endpoint)
            .then((response: Response) => response.json())
            .then((json: any) => this.onHashReceived(json.payload));
    }

    public static bootstrap(): void {
        document.querySelectorAll(`.${RandomHash.HostDomClass}`).forEach((host: HTMLElement) => new RandomHash(host));
    }
}

(function() {
    window.addEventListener("load", RandomHash.bootstrap);
})();
