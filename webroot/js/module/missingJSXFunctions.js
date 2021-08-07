export function nvl(nullableValue, defaultValue) {
    return nullableValue != null ? nullableValue : defaultValue;
}