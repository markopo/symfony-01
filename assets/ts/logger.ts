// NOT RECOMMENDED! use ES6 modules instead. https://stackoverflow.com/questions/37565709/how-to-use-namespaces-with-import-in-typescript
export namespace Logger {
    export function log(parameters: { msg: string }): void {
        const msg = parameters.msg;
        console.log(msg);
    }
    export function error(parameters: { msg: string }): void {
        const msg = parameters.msg;
        console.error(msg);
    }
}