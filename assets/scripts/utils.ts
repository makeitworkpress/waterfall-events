/**
 * Small shared helpers used across the front-end modules.
 */

/**
 * Runs a callback once the document is ready, also when the script is parsed late.
 *
 * @param callback The function to execute.
 */
export function ready(callback: () => void): void {
    if (document.readyState !== 'loading') {
        callback();
        return;
    }

    document.addEventListener('DOMContentLoaded', () => callback(), { once: true });
}

/**
 * Reads a configuration object that PHP printed onto the window as a global variable.
 *
 * @param name The name of the global variable, such as `wfeMapmy-map-id`.
 * @returns The configuration, or undefined when it is not present.
 */
export function getGlobalConfig<T>(name: string): T | undefined {
    return (window as unknown as Record<string, T | undefined>)[name];
}

/**
 * Determines whether we are running inside the Elementor editor preview, rather than
 * on the actual front-end. Elementor adds an `elementor-preview` query argument there.
 */
export function isElementorPreview(): boolean {
    return Boolean(new URLSearchParams(window.location.search).get('elementor-preview'));
}
