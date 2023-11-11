export const format = (date, options = [], separator = '-') => {
    function format(option) {
        const formatter = new Intl.DateTimeFormat('en', option)
        return formatter.format(date)
    }

    return options.map(format).join(separator)
}