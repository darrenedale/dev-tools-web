
export class HexitCodec
{
    private m_raw?: string;
    private m_encoded?: string;

    public get raw(): string
    {
        if (undefined === this.m_raw) {
            this.m_raw = HexitCodec.fromHexits(this.m_encoded);
        }

        return this.m_raw;
    }

    public get encoded(): string
    {
        if (undefined === this.m_encoded) {
            this.m_encoded = HexitCodec.toHexits(this.m_raw);
        }

        return this.m_encoded;
    }

    public set raw(raw: string)
    {
        this.m_raw = raw;
        this.m_encoded = undefined;
    }

    public set encoded(encoded: string)
    {
        this.m_encoded = encoded;
        this.m_raw = undefined;
    }

    private static toHexits(raw: string): string
    {
        return "";
    }

    private static fromHexits(raw: string): string
    {
        return "";
    }
}
