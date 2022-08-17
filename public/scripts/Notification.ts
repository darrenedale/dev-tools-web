type NotificationContent = Node | Node[] | string;

export class Notification
{
    public static readonly DefaultDuration = 5000;
    private static m_container?: HTMLElement;

    private m_element: HTMLElement;
    private m_duration: number;

    private constructor(content: NotificationContent, duration: number, className: string) {
        if (undefined === Notification.m_container) {
            Notification.createContainer();
        }

        this.m_duration = (0 < duration ? duration : Notification.DefaultDuration);
        this.m_element = this.createElement();
        this.m_element.classList.add(className);

        if ("string" === typeof content) {
            this.m_element.append(document.createTextNode(content));
        } else if (Array.isArray(content)) {
            this.m_element.append(...content);
        } else {
            this.m_element.append(content);
        }
    }

    public show(): void
    {
        Notification.m_container.append(this.m_element);
        window.setTimeout(() => this.hide(), Notification.DefaultDuration)
    }


    public hide(): void
    {
        this.m_element.remove();
    }

    protected createElement(): HTMLElement
    {
        const element = document.createElement("DIV");
        element.classList.add("notification");
        return element;
    }

    private static createContainer()
    {
        Notification.m_container = document.createElement("DIV");
        this.m_container.classList.add("notifications-container");
        document.body.append(this.m_container);
    }

    public static information(content: NotificationContent, duration?: number): Notification
    {
        const notification = new Notification(content, duration ?? Notification.DefaultDuration, "information");
        notification.show();
        return notification;
    }

    public static warning(content: NotificationContent, duration?: number): Notification
    {
        const notification = new Notification(content, duration ?? Notification.DefaultDuration, "warning");
        notification.show();
        return notification;
    }

    public static error(content: NotificationContent, duration?: number): Notification
    {
        const notification = new Notification(content, duration ?? Notification.DefaultDuration, "error");
        notification.show();
        return notification;
    }
}
