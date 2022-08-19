import {Notification} from "./Notification.js";
import {HexitCodec} from "./HexitCodec.js";

class Codec
{
    /**
     * How long after the user stops typing to wait for the next key before fetching the hash.
     *
     * If the user presses a key within this time the hash is not fetched, to avoid spamming the backend with requests.
     */
    protected static readonly RefreshTimerDuration = 350;

    public static readonly HostDomClass = "codec-container";
    private static readonly RawDomClass = "codec-raw";
    private static readonly EncodedDomClass = "codec-encoded";
    private static readonly CopyRawBinaryDomClass = "codec-copy-raw-binary";
    private static readonly CopyRawHexitsDomClass = "codec-copy-raw-hexits";
    private static readonly CopyEncodedDomClass = "codec-copy-encoded";

    private readonly m_host: HTMLElement;
    private readonly m_csrf: string;
    private readonly m_algorithm: string;
    private readonly m_raw: HTMLTextAreaElement;
    private readonly m_encoded: HTMLTextAreaElement;
    private readonly m_copyRawBinary: HTMLButtonElement;
    private readonly m_copyRawHexits: HTMLButtonElement;
    private readonly m_copyEncoded: HTMLButtonElement;
    private m_refreshTimerId: number;

    public constructor(host: HTMLElement)
    {
        this.m_refreshTimerId = 0;
        this.m_host = host;
        this.m_algorithm = host.dataset.algorithm;
        this.m_csrf = host.dataset.csrf;
        this.m_raw = host.querySelector(`textarea.${Codec.RawDomClass}`);
        this.m_encoded = host.querySelector(`textarea.${Codec.EncodedDomClass}`);
        this.m_copyRawBinary = host.querySelector(`button.${Codec.CopyRawBinaryDomClass}`);
        this.m_copyRawHexits = host.querySelector(`button.${Codec.CopyRawHexitsDomClass}`);
        this.m_copyEncoded = host.querySelector(`button.${Codec.CopyEncodedDomClass}`);
        this.bindEvents();

        if ("" === this.rawHexits) {
            this.m_copyRawBinary.disabled = true;
            this.m_copyRawHexits.disabled = true;
        }

        if ("" === this.encodedContent) {
            this.m_copyEncoded.disabled = true;
        }
    }

    protected get encodeEndpoint(): string
    {
        return `/api/codecs/${this.m_algorithm}/encode`;
    }

    protected get decodeEndpoint(): string
    {
        return `/api/codecs/${this.m_algorithm}/decode`;
    }

    public get algorithm(): string
    {
        return this.m_algorithm;
    }

    public get rawHexits(): string
    {
        return this.m_raw.value;
    }

    public get rawBinary(): string
    {
        const codec = new HexitCodec();
        codec.encoded = this.rawHexits;
        return codec.raw;
    }

    public get encodedContent(): string
    {
        return this.m_encoded.value;
    }

    public set encodedContent(content: string)
    {
        this.m_encoded.value = content;
    }

    public set rawHexits(hexits: string)
    {
        this.m_raw.value = hexits;
    }

    public set rawBinary(binary: string)
    {
        const codec = new HexitCodec();
        codec.raw = binary;
        this.rawHexits = codec.encoded;
    }

    public get rawElement(): HTMLTextAreaElement
    {
        return this.m_raw;
    }

    public get encodedElement(): HTMLTextAreaElement
    {
        return this.m_encoded;
    }

    public get hostElement(): HTMLElement
    {
        return this.m_host;
    }

    private bindEvents(): void
    {
        this.m_encoded.addEventListener("keyup", (event: KeyboardEvent) => this.onEncodedKeyPress(event));
        this.m_raw.addEventListener("keyup", (event: KeyboardEvent) => this.onRawKeyPress(event));
        this.m_copyRawHexits.addEventListener("click", (event: MouseEvent) => this.onCopyRawHexitsClicked(event));
        this.m_copyRawBinary.addEventListener("click", (event: MouseEvent) => this.onCopyRawBinaryClicked(event));
        this.m_copyEncoded.addEventListener("click", (event: MouseEvent) => this.onCopyEncodedClicked(event));
    }

    protected fetchEncoded(): void
    {
        if ("" === this.rawHexits) {
            this.m_copyEncoded.disabled = true;
            this.m_copyRawHexits.disabled = true;
            this.m_copyRawBinary.disabled = true;
            this.encodedContent = "";
            return;
        }

        this.m_copyEncoded.disabled = false;
        this.m_copyRawHexits.disabled = false;
        this.m_copyRawBinary.disabled = false;
        const body = new FormData();
        body.set("_token", this.m_csrf);
        body.set("raw", this.rawHexits);

        fetch(this.encodeEndpoint, {
            method: "POST",
            body: body
        })
            .then((response: Response) => response.json())
            .then((json) => this.onEncodedReceived(json.payload));
    }

    protected fetchDecoded(): void
    {
        if ("" === this.encodedContent) {
            this.m_copyEncoded.disabled = true;
            this.m_copyRawHexits.disabled = true;
            this.m_copyRawBinary.disabled = true;
            this.rawHexits = "";
            return;
        }

        this.m_copyEncoded.disabled = false;
        this.m_copyRawHexits.disabled = false;
        this.m_copyRawBinary.disabled = false;
        const body = new FormData();
        body.set("_token", this.m_csrf);
        body.set("content", this.encodedContent);

        fetch(this.decodeEndpoint, {
            method: "POST",
            body: body
        })
            .then((response: Response) => response.json())
            .then((json) => this.onDecodedReceived(json.payload));
    }

    protected onRawTimerTimeout(): void
    {
        this.fetchEncoded();
    }

    protected onEncodedTimerTimeout(): void
    {
        this.fetchDecoded();
    }

    protected onEncodedKeyPress(event: KeyboardEvent): void
    {
        if (0 !== this.m_refreshTimerId) {
            window.clearTimeout(this.m_refreshTimerId);
        }

        this.m_refreshTimerId = window.setTimeout(() => this.onEncodedTimerTimeout(), Codec.RefreshTimerDuration);
    }

    protected onRawKeyPress(event: KeyboardEvent): void
    {
        if (0 !== this.m_refreshTimerId) {
            window.clearTimeout(this.m_refreshTimerId);
        }

        this.m_refreshTimerId = window.setTimeout(() => this.onRawTimerTimeout(), Codec.RefreshTimerDuration);
    }

    protected onEncodedReceived(encoded: string): void
    {
        this.encodedContent = encoded;
    }

    protected onDecodedReceived(hexits: string): void
    {
        this.rawHexits = hexits;
    }

    protected onCopyEncodedClicked(event: MouseEvent): void
    {
        navigator.clipboard.writeText(this.encodedContent)
            .then(() => Notification.information(`The ${this.algorithm} encoded content has been copied to the clipboard.`));
    }

    protected onCopyRawHexitsClicked(event: MouseEvent): void
    {
        navigator.clipboard.writeText(this.rawHexits)
            .then(() => Notification.information(`The raw hexits have been copied to the clipboard.`));
    }

    protected onCopyRawBinaryClicked(event: MouseEvent): void
    {
        navigator.clipboard.write([new ClipboardItem({"application/octet-stream": this.rawBinary})])
            .then(() => Notification.information(`The raw binary data has been copied to the clipboard.`));
    }

    public static bootstrap(): void
    {
        document.querySelectorAll(`.${Codec.HostDomClass}`).forEach((host: HTMLElement) => new Codec(host));
    }
}

(function ()
{
    window.addEventListener("load", Codec.bootstrap);
})();
