class Decoder
{
    public static readonly HostDomClass = "decoder-container";
    private static readonly ContentDomClass = "decoder-content";
    private static readonly DecodeButtonDomClass = "decoder-decode";
    private static readonly ClearButtonDomClass = "decoder-clear";

    private readonly m_host: HTMLElement;
    private readonly m_csrf: string;
    private readonly m_algorithm: string;
    private readonly m_content: HTMLTextAreaElement;
    private readonly m_decode: HTMLButtonElement;
    private readonly m_clear: HTMLButtonElement;

    public constructor(host: HTMLElement)
    {
        this.m_host = host;
        this.m_algorithm = host.dataset.algorithm;
        this.m_csrf = host.dataset.csrf;
        this.m_content = host.querySelector(`textarea.${Decoder.ContentDomClass}`);
        this.m_decode = host.querySelector(`button.${Decoder.DecodeButtonDomClass}`);
        this.m_clear = host.querySelector(`button.${Decoder.ClearButtonDomClass}`);
        this.syncButtonStates();
        this.bindEvents();
    }

    public get algorithm(): string
    {
        return this.m_algorithm;
    }

    public get content(): string
    {
        return this.m_content.value;
    }

    public set content(content: string)
    {
        this.m_content.value = content;
    }

    public get contentElement(): HTMLTextAreaElement
    {
        return this.m_content;
    }

    public get hostElement(): HTMLElement
    {
        return this.m_host;
    }

    private bindEvents(): void
    {
        this.m_content.addEventListener("keyup", (event: KeyboardEvent) => this.onContentKeyPressed(event));
        this.m_clear.addEventListener("click", (event: MouseEvent) => this.onClearClicked(event));
        // this.m_decode.addEventListener("click", (event: MouseEvent) => this.onDecodeClicked(event));
    }

    protected syncButtonStates()
    {
        if ("" === this.content) {
            this.m_clear.disabled = true;
            this.m_decode.disabled = true;
        } else {
            this.m_clear.disabled = false;
            this.m_decode.disabled = false;
        }
    }

    // private static saveDecodedContent(decoded: Blob): void
    // {
    //     const url = window.URL.createObjectURL(decoded);
    //     window.location.href = url;
    // }
    //
    protected onContentKeyPressed(event: KeyboardEvent): void
    {
        this.syncButtonStates();
    }

    // protected onDecodedReceived(response: Response): void
    // {
    //     if (!response.ok) {
    //         Notification.error("The content could not be decoded.");
    //         return;
    //     }
    //
    //     response.blob().then(Decoder.saveDecodedContent);
    // }

    protected onClearClicked(event: MouseEvent): void
    {
        this.content = "";
    }

    // protected onDecodeClicked(event: MouseEvent): void
    // {
    //     const data = new FormData();
    //     data.set("_token", this.m_csrf);
    //     data.set("content", this.content);
    //     fetch("/decoder/" + encodeURIComponent(this.algorithm), {
    //         method: 'POST',
    //         body: data,
    //     })
    //         .then((response: Response) => this.onDecodedReceived(response));
    // }
    //
    public static bootstrap(): void
    {
        document.querySelectorAll(`.${Decoder.HostDomClass}`).forEach((host: HTMLElement) => new Decoder(host));
    }
}

(function ()
{
    window.addEventListener("load", Decoder.bootstrap);
})();
