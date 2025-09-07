import { clsx, type ClassValue } from "clsx";
import { twMerge } from "tailwind-merge";

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

// cookies utils
// https://javascript.info/cookie

// see cookies in command line :
// document.cookie.split("; ").forEach((cookie) => console.log(cookie));

// returns the cookie with the given name,
// or undefined if not found
export function getCookie(name: string): string | undefined {
    const matches = document.cookie.match(new RegExp(
        "(?:^|; )" + name.replace(/([.$?*|{}()[\]\\/+^])/g, "\\$1") + "=([^;]*)"
    ));
    return matches ? decodeURIComponent(matches[1]) : undefined;
}

type CookieAttributes = {
    path?: string;
    domain?: string;
    secure?: boolean;
    sameSite?: "strict" | "lax" | "none";
    maxAge?: number;
    expires?: string | Date;
};
type CookieAttribute = keyof CookieAttributes;

export function setCookie(name: string, value: string, attributes: CookieAttributes = {}): void {
    attributes = {
        path: "/",
        sameSite: "lax",
        maxAge: 60 * 60 * 24 * 31, // 31 days
        secure: true,
        ...attributes,
    };
    if (attributes.expires instanceof Date) {
        attributes.expires = attributes.expires.toUTCString();
    }
    let updatedCookie = `${encodeURIComponent(name)}=${encodeURIComponent(value)}`;
    const attributeNameMap: Record<string, string> = {
        maxAge: "max-age",
        sameSite: "samesite",
    };
    for (const attributeKey in attributes) {
        if (Object.prototype.hasOwnProperty.call(attributes, attributeKey)) {
            const cookieAttributeName = attributeNameMap[attributeKey] || attributeKey;
            updatedCookie += "; " + cookieAttributeName;
            const attributeValue = attributes[attributeKey as CookieAttribute];
            if (attributeValue !== true) {
                updatedCookie += `=${attributeValue}`;
            }
        }
    }
    document.cookie = updatedCookie;
};
// Example of use:
// setCookie("user", "John", {secure: true, "max-age": 3600});

export function deleteCookie(name: string): void {
    setCookie(name, "", {
        maxAge: -1
    })
}
