class Decoder
{
    public static readonly HostDomClass = "decoder-container";
    private static readonly ContentDomClass = "decoder-content";
    private static readonly FileDomClass = "decoder-file";
    private static readonly DecodeButtonDomClass = "decoder-decode";
    private static readonly ClearButtonDomClass = "decoder-clear";

    private readonly m_host: HTMLElement;
    private readonly m_algorithm: string;
    private readonly m_content: HTMLTextAreaElement;
    private readonly m_file: HTMLInputElement;
    private readonly m_decode: HTMLButtonElement;
    private readonly m_clear: HTMLButtonElement;

    public constructor(host: HTMLElement)
    {
        this.m_host = host;
        this.m_algorithm = host.dataset.algorithm;
        this.m_content = host.querySelector(`textarea.${Decoder.ContentDomClass}`);
        this.m_file = host.querySelector(`input.${Decoder.FileDomClass}`);
        this.m_decode = host.querySelector(`button.${Decoder.DecodeButtonDomClass}`);
        this.m_clear = host.querySelector(`button.${Decoder.ClearButtonDomClass}`);
        this.syncButtonStates();
        this.syncSubmitAction();
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

    public get fileElement(): HTMLInputElement
    {
        return this.m_file;
    }

    public get hasFile(): boolean
    {
        return 0 < this.fileElement.files.length;
    }

    private bindEvents(): void
    {
        this.m_content.addEventListener("keyup", (event: KeyboardEvent) => this.onContentKeyPressed(event));
        this.m_clear.addEventListener("click", (event: MouseEvent) => this.onClearClicked(event));
        this.m_file.addEventListener("change", (event: Event) => this.onFileChanged(event));
        // this.m_decode.addEventListener("click", (event: MouseEvent) => this.onDecodeClicked(event));
    }

    protected syncButtonStates(): void
    {
        if ("" === this.content && !this.hasFile) {
            this.m_clear.disabled = true;
            this.m_decode.disabled = true;
        } else {
            this.m_clear.disabled = false;
            this.m_decode.disabled = false;
        }
    }

    protected syncSubmitAction(): void
    {
        if (this.hasFile) {
            this.m_decode.formAction = `${this.m_decode.form.action}/file`;
        } else {
            this.m_decode.formAction = this.m_decode.form.action;
        }
    }

    protected onContentKeyPressed(event: KeyboardEvent): void
    {
        if ("" !== this.content) {
            this.m_file.files = new FileList();
        }

        this.syncSubmitAction();
        this.syncButtonStates();
    }

    protected onFileChanged(event: Event): void
    {
        this.syncSubmitAction();
        this.syncButtonStates();
    }

    protected onClearClicked(event: MouseEvent): void
    {
        this.content = "";
    }

    public static bootstrap(): void
    {
        document.querySelectorAll(`.${Decoder.HostDomClass}`).forEach((host: HTMLElement) => new Decoder(host));
    }
}

(function ()
{
    window.addEventListener("load", Decoder.bootstrap);
})();
