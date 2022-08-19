import {Notification} from "./Notification.js";

class RegexTester
{
    public static readonly HostDomClass = "regex-tester";
    public static readonly RegexInputDomClass = "regex";
    public static readonly CopyRegexButtonDomClass = "regex-copy";
    public static readonly CopyTestStringButtonDomClass = "test-string-copy";
    public static readonly TestStringInputDomClass = "test-string";
    public static readonly ResultIconDomClass = "result";

    private readonly m_host: HTMLElement;
    private readonly m_regex: HTMLInputElement;
    private readonly m_copyRegex: HTMLButtonElement;
    private readonly m_copyTestString: HTMLButtonElement;
    private readonly m_testString: HTMLInputElement;
    private readonly m_result: HTMLSpanElement;
    private readonly m_originalRegexTooltip: string;
    private m_resultUpdateTimerId: number;

    public constructor(host: HTMLElement) {
        this.m_resultUpdateTimerId = 0;
        this.m_host = host;
        this.m_regex = host.querySelector(`.${RegexTester.RegexInputDomClass}`);
        this.m_originalRegexTooltip = this.m_regex.title;
        this.m_copyRegex = host.querySelector(`.${RegexTester.CopyRegexButtonDomClass}`);
        this.m_copyTestString = host.querySelector(`.${RegexTester.CopyTestStringButtonDomClass}`);
        this.m_testString = host.querySelector(`.${RegexTester.TestStringInputDomClass}`);
        this.m_result = host.querySelector(`.${RegexTester.ResultIconDomClass}`);

        this.updateResult();
        this.bindComponents();
    }

    public get regexInput(): HTMLInputElement
    {
        return this.m_regex;
    }

    public get regex(): string
    {
        return this.regexInput.value;
    }

    public get testStringInput(): HTMLInputElement
    {
        return this.m_testString;
    }

    public get testString(): string
    {
        return this.testStringInput.value;
    }

    public get resultIcon(): HTMLSpanElement
    {
        return this.m_result;
    }

    protected updateResult(): void
    {
        let regex: RegExp;

        try {
            this.regexInput.title = this.m_originalRegexTooltip;
            this.regexInput.classList.remove("error");
            regex = new RegExp(this.regex);
        } catch (err) {
            if (err instanceof SyntaxError) {
                this.regexInput.title = err.message;
                this.regexInput.classList.add("error");
                Notification.error("Invalid regular expression.");
            } else {
                console.error(err);
            }

            return;
        }

        if (regex.test(this.testString)) {
            this.testStringInput.classList.add("regex-match");
            this.testStringInput.classList.remove("regex-no-match");
            this.resultIcon.classList.remove("fa-xmark");
            this.resultIcon.classList.add("fa-check");
        } else {
            this.testStringInput.classList.remove("regex-match");
            this.testStringInput.classList.add("regex-no-match");
            this.resultIcon.classList.remove("fa-check");
            this.resultIcon.classList.add("fa-xmark");
        }
    }

    protected restartResultUpdateTimer(): void
    {
        if (0 !== this.m_resultUpdateTimerId) {
            window.clearTimeout(this.m_resultUpdateTimerId);
        }

        this.m_resultUpdateTimerId = window.setTimeout(() => this.updateResult(), 250);
    }

    protected onRegexKeyPress(event: KeyboardEvent): void
    {
        this.restartResultUpdateTimer();
    }

    protected onTestStringKeyPress(event: KeyboardEvent): void
    {
        this.restartResultUpdateTimer();
    }

    protected onCopyRegexClicked(event: MouseEvent): void
    {
        navigator.clipboard.writeText(this.m_regex.innerText)
            .then(() => Notification.information("The regular expression has been copied to the clipboard."));
    }

    protected onCopyTestStringClicked(event: MouseEvent): void
    {
        navigator.clipboard.writeText(this.testString)
            .then(() => Notification.information("The test string has been copied to the clipboard."));
    }

    private bindComponents(): void
    {
        this.m_copyRegex.addEventListener("click", (event: MouseEvent) => this.onCopyRegexClicked(event));
        this.m_copyTestString.addEventListener("click", (event: MouseEvent) => this.onCopyTestStringClicked(event));
        this.m_regex.addEventListener("keyup", (event: KeyboardEvent) => this.onRegexKeyPress(event));
        this.m_testString.addEventListener("keyup", (event: KeyboardEvent) => this.onTestStringKeyPress(event));
    }

    public static bootstrap(): void
    {
        document.querySelectorAll(`.${RegexTester.HostDomClass}`).forEach((host: HTMLElement) => new RegexTester(host));
    }
}

(function() {
    window.addEventListener("load", RegexTester.bootstrap);
})();