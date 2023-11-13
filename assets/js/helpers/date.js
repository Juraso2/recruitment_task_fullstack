export const formatOptions = [{year: 'numeric'}, {month: '2-digit'}, {day: '2-digit'}]

export const format = (date, options = [], separator = '-') => {
    function format(option) {
        const formatter = new Intl.DateTimeFormat('en', option)
        return formatter.format(date)
    }

    return options.map(format).join(separator)
}

export const isWeekend = (date) => {
    const day = date.getDay()
    return day === 6 || day === 0
}

export const isLastDayOfMonth = (date) => {
    const lastDayOfMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0)
    return date.getDate() === lastDayOfMonth.getDate()
}

export const isFirstDayOfMonth = (date) => {
    return date.getDate() === 1
}

export const findNextWorkingDay = (date) => {
    const nextDay = new Date(date)
    nextDay.setDate(nextDay.getDate() + 1)

    if (isWeekend(nextDay)) {
        return findNextWorkingDay(nextDay)
    }

    return nextDay
}

export const findPreviousWorkingDay = (date) => {
    const previousDay = new Date(date)
    previousDay.setDate(previousDay.getDate() - 1)

    if (isWeekend(previousDay)) {
        return findPreviousWorkingDay(previousDay)
    }

    return previousDay
}