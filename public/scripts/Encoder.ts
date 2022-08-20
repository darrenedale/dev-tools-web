class Encoder
{
    public static readonly HostDomClass = "encoder-container";
    private static readonly FileDomClass = "encoder-file";
    private static readonly EncodeButtonDomClass = "encoder-encode";
    private static readonly ClearButtonDomClass = "encoder-clear";

    private readonly m_host: HTMLElement;
    private readonly m_csrf: string;
    private readonly m_algorithm: string;
    private readonly m_file: HTMLInputElement;
    private readonly m_encode: HTMLButtonElement;
    private readonly m_clear: HTMLButtonElement;

    public constructor(host: HTMLElement)
    {
        this.m_host = host;
        this.m_algorithm = host.dataset.algorithm;
        this.m_csrf = host.dataset.csrf;
        this.m_file = host.querySelector(`textarea.${Encoder.FileDomClass}`);
        this.m_encode = host.querySelector(`button.${Encoder.EncodeButtonDomClass}`);
        this.m_clear = host.querySelector(`button.${Encoder.ClearButtonDomClass}`);
        this.bindEvents();
    }

    public get algorithm(): string
    {
        return this.m_algorithm;
    }

    public get fileElement(): HTMLInputElement
    {
        return this.m_file;
    }

    public get hostElement(): HTMLElement
    {
        return this.m_host;
    }

    private bindEvents(): void
    {
        this.m_clear.addEventListener("click", (event: MouseEvent) => this.onClearClicked(event));
    }

    protected onClearClicked(event: MouseEvent): void
    {
        this.m_file.value = "";
    }

    public static bootstrap(): void
    {
        document.querySelectorAll(`.${Encoder.HostDomClass}`).forEach((host: HTMLElement) => new Encoder(host));
    }
}

(function ()
{
    window.addEventListener("load", Encoder.bootstrap);
})();
