import {Notification} from "./Notification.js";

class Timestamp
{
    public static readonly HostDomClass = "timestamp-container";
    private static readonly TimestampDomClass = "timestamp-timestamp";
    private static readonly YearDomClass = "timestamp-year";
    private static readonly MonthDomClass = "timestamp-month";
    private static readonly DayDomClass = "timestamp-day";
    private static readonly HourDomClass = "timestamp-hour";
    private static readonly MinuteDomClass = "timestamp-minute";
    private static readonly SecondDomClass = "timestamp-second";
    private static readonly CopyTimestampDomClass = "timestamp-copy";
    private static readonly NowDomClass = "timestamp-now";
    private static readonly ContinuousUpdateDomClass = "timestamp-continuous-update";
    private static readonly ResetTimestampDomClass = "timestamp-reset";

    private readonly m_host: HTMLElement;
    private readonly m_timestamp: HTMLInputElement;
    private readonly m_year: HTMLInputElement;
    private readonly m_month: HTMLSelectElement;
    private readonly m_day: HTMLInputElement;
    private readonly m_hour: HTMLInputElement;
    private readonly m_minute: HTMLInputElement;
    private readonly m_second: HTMLInputElement;
    private readonly m_copyTimestamp: HTMLButtonElement;
    private readonly m_now: HTMLButtonElement;
    private readonly m_continuousUpdate: HTMLInputElement;
    private readonly m_reset: HTMLButtonElement;
    private m_refreshTimerId?: number;
    private m_continuouslyUpdateTimerId?: number;

    public constructor(host: HTMLElement)
    {
        this.m_host = host;
        this.m_timestamp = host.querySelector(`input.${Timestamp.TimestampDomClass}`);
        this.m_year = host.querySelector(`input.${Timestamp.YearDomClass}`);
        this.m_month = host.querySelector(`select.${Timestamp.MonthDomClass}`);
        this.m_day = host.querySelector(`input.${Timestamp.DayDomClass}`);
        this.m_hour = host.querySelector(`input.${Timestamp.HourDomClass}`);
        this.m_minute = host.querySelector(`input.${Timestamp.MinuteDomClass}`);
        this.m_second = host.querySelector(`input.${Timestamp.SecondDomClass}`);
        this.m_copyTimestamp = host.querySelector(`button.${Timestamp.CopyTimestampDomClass}`);
        this.m_now = host.querySelector(`button.${Timestamp.NowDomClass}`);
        this.m_continuousUpdate = host.querySelector(`input.${Timestamp.ContinuousUpdateDomClass}`);
        this.m_reset = host.querySelector(`button.${Timestamp.ResetTimestampDomClass}`);

        if (0 !== this.timestamp) {
            this.setDateFromTimestamp();
        } else if (1970 !== this.year || 1 !== this.month || 1 != this.day || 0 != this.hour || 0 != this.minute || 0 != this.second) {
            this.setTimestampFromDate();
        }

        this.bindEvents();
    }

    public showNow(): void
    {
        this.timestamp = Math.floor(Date.now() / 1000);
    }

    public get date(): Date
    {
        return new Date(this.timestamp * 1000);
    }

    public get timestamp(): number
    {
        return Number.parseInt(this.m_timestamp.value);
    }

    public set timestamp(timestamp: number)
    {
        this.m_timestamp.value = `${timestamp}`;
        this.setDateFromTimestamp();
    }

    public get year(): number
    {
        return Number.parseInt(this.m_year.value);
    }

    public get month(): number
    {
        return Number.parseInt(this.m_month.value);
    }

    public get day(): number
    {
        return Number.parseInt(this.m_day.value);
    }

    public get hour(): number
    {
        return Number.parseInt(this.m_hour.value);
    }

    public get minute(): number
    {
        return Number.parseInt(this.m_minute.value);
    }

    public get second(): number
    {
        return Number.parseInt(this.m_second.value);
    }

    public get timestampElement(): HTMLInputElement
    {
        return this.m_timestamp;
    }

    public get yearElement(): HTMLInputElement
    {
        return this.m_year;
    }

    public get monthElement(): HTMLSelectElement
    {
        return this.m_month;
    }

    public get dayElement(): HTMLInputElement
    {
        return this.m_day;
    }

    public get hourElement(): HTMLInputElement
    {
        return this.m_hour;
    }

    public get minuteElement(): HTMLInputElement
    {
        return this.m_minute;
    }

    public get secondElement(): HTMLInputElement
    {
        return this.m_second;
    }

    public get nowButton(): HTMLButtonElement
    {
        return this.m_now;
    }

    public get copyButton(): HTMLButtonElement
    {
        return this.m_copyTimestamp;
    }

    public get resetButton(): HTMLButtonElement
    {
        return this.m_reset;
    }

    public get continuouslyUpdateCheckbox(): HTMLInputElement
    {
        return this.m_continuousUpdate;
    }

    public get hostElement(): HTMLElement
    {
        return this.m_host;
    }

    public get continuouslyUpdate(): boolean
    {
        return this.m_continuousUpdate.checked;
    }

    public set continuouslyUpdate(continuous: boolean)
    {
        this.m_continuousUpdate.checked = continuous;
    }

    private bindEvents(): void
    {
        this.m_timestamp.addEventListener("input", (event: Event) => this.onTimestampChanged(event));

        this.m_year.addEventListener("input", (event: Event) => this.onYearChanged(event));
        this.m_month.addEventListener("input", (event: Event) => this.onMonthChanged(event));
        this.m_day.addEventListener("input", (event: Event) => this.onDayChanged(event));
        this.m_hour.addEventListener("input", (event: Event) => this.onHourChanged(event));
        this.m_minute.addEventListener("input", (event: Event) => this.onMinuteChanged(event));
        this.m_second.addEventListener("input", (event: Event) => this.onSecondChanged(event));

        this.m_copyTimestamp.addEventListener("click", (event: MouseEvent) => this.onCopyTimestampClicked(event));
        this.m_now.addEventListener("click", (event: MouseEvent) => this.onNowClicked(event));
        this.m_continuousUpdate.addEventListener("click", (event: MouseEvent) => this.onContinuouslyUpdateClicked(event));
        this.m_reset.addEventListener("click", (event: MouseEvent) => this.onResetClicked(event));
    }

    /**
     * Turn the continuous update timer on or off based on the checkbox state.
     */
    private syncContinuouslyUpdateTimer(): void
    {
        if (undefined !== this.m_continuouslyUpdateTimerId) {
            window.clearInterval(this.m_continuouslyUpdateTimerId);
        }

        if (this.continuouslyUpdate) {
            this.showNow();
            this.m_continuouslyUpdateTimerId = window.setInterval(() => this.showNow(), 1000);
        }
    }

    protected setDateFromTimestamp(): void
    {
        const date = new Date(this.timestamp * 1000);
        this.m_year.value = `${date.getUTCFullYear()}`;
        this.m_month.value = `${date.getUTCMonth() + 1}`;
        this.m_day.value = `${date.getUTCDate()}`;
        this.m_hour.value = `${date.getUTCHours()}`;
        this.m_minute.value = `${date.getUTCMinutes()}`;
        this.m_second.value = `${date.getUTCSeconds()}`;
    }


    protected setTimestampFromDate(): void
    {
        const date = new Date(this.year, this.month - 1, this.day, this.hour, this.minute, this.second);
        const timestamp = Math.floor((date.getTime() - (date.getTimezoneOffset() * 60000)) / 1000);

        if (Number.isNaN(timestamp)) {
            this.m_timestamp.value = "";
            Notification.error("Invalid date.");
        } else {
            this.m_timestamp.value = `${timestamp}`;
        }
    }

    protected restartTimestampRefreshTimer(): void
    {
        if (undefined !== this.m_refreshTimerId) {
            window.clearTimeout(this.m_refreshTimerId);
        }

        this.m_refreshTimerId = window.setTimeout(() => this.onTimestampRefreshTimeout());
    }

    protected restartDateRefreshTimer(): void
    {
        if (undefined !== this.m_refreshTimerId) {
            window.clearTimeout(this.m_refreshTimerId);
        }

        this.m_refreshTimerId = window.setTimeout(() => this.onDateRefreshTimeout());
    }

    protected onDateRefreshTimeout(): void
    {
        delete this.m_refreshTimerId;
        this.setDateFromTimestamp();
    }

    protected onTimestampRefreshTimeout(): void
    {
        delete this.m_refreshTimerId;
        this.setTimestampFromDate();
    }

    protected onTimestampChanged(event: Event): void
    {
        this.restartDateRefreshTimer();
    }

    protected onYearChanged(event: Event): void
    {
        this.restartTimestampRefreshTimer();
    }

    protected onMonthChanged(event: Event): void
    {
        this.restartTimestampRefreshTimer();
    }

    protected onDayChanged(event: Event): void
    {
        this.restartTimestampRefreshTimer();
    }

    protected onHourChanged(event: Event): void
    {
        this.restartTimestampRefreshTimer();
    }

    protected onMinuteChanged(event: Event): void
    {
        this.restartTimestampRefreshTimer();
    }

    protected onSecondChanged(event: Event): void
    {
        this.restartTimestampRefreshTimer();
    }

    protected onCopyTimestampClicked(event: MouseEvent): void
    {
        navigator.clipboard.writeText(`${this.timestamp}`)
            .then(() => Notification.information(`The timestamp ${this.timestamp} was copied to the clipboard`));
    }

    protected onNowClicked(event: MouseEvent): void
    {
        this.showNow();
    }

    protected onContinuouslyUpdateClicked(event: MouseEvent): void
    {
        this.syncContinuouslyUpdateTimer();
    }

    protected onResetClicked(event: MouseEvent): void
    {
        this.timestamp = 0;
    }

    public static bootstrap(): void
    {
        document.querySelectorAll(`.${Timestamp.HostDomClass}`).forEach((host: HTMLElement) => new Timestamp(host));
    }
}

(function ()
{
    window.addEventListener("load", Timestamp.bootstrap);
})();
