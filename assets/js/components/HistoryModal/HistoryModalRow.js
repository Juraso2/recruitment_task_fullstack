import React from "react";
import ArrowIndicator from "../ArrowIndicator";
import PropTypes from "prop-types";

const HistoryModalRow = ({ history, previousHistory, isLast }) => {
    const getClassName = () => {
        if (history.isHighest) {
            return 'table-success'
        }

        if (history.isLowest) {
            return 'table-danger'
        }

        return null
    }

    return (
        <tr className={getClassName()}>
            <td>{history.date}</td>
            <td>
                {isLast ? (
                    <ArrowIndicator from={0} to={0}/>
                ) : (
                    <ArrowIndicator
                        from={history.exchangeRate || 0}
                        to={previousHistory.exchangeRate || 0}
                    />
                )}
                {history.exchangeRate}
            </td>
            <td>
                {isLast ? (
                    <ArrowIndicator from={0} to={0}/>
                ) : (
                    <ArrowIndicator
                        from={history.purchaseRate || 0}
                        to={previousHistory.purchaseRate || 0}
                    />
                )}
                {history.purchaseRate}
            </td>
            <td>
                {isLast ? (
                    <ArrowIndicator from={0} to={0}/>
                ) : (
                    <ArrowIndicator
                        from={history.sellRate || 0}
                        to={previousHistory.sellRate || 0}
                    />
                )}
                {history.sellRate}
            </td>
        </tr>
    )
}

HistoryModalRow.propTypes = {
    history: PropTypes.shape({
        date: PropTypes.string.isRequired,
        exchangeRate: PropTypes.number.isRequired,
        purchaseRate: PropTypes.number.isRequired,
        sellRate: PropTypes.number.isRequired,
        isHighest: PropTypes.bool.isRequired,
        isLowest: PropTypes.bool.isRequired,
    }).isRequired,
    previousHistory: PropTypes.shape({
        date: PropTypes.string.isRequired,
        exchangeRate: PropTypes.number.isRequired,
        purchaseRate: PropTypes.number.isRequired,
        sellRate: PropTypes.number.isRequired,
        isHighest: PropTypes.bool.isRequired,
        isLowest: PropTypes.bool.isRequired,
    }),
    isLast: PropTypes.bool.isRequired,
}

export default HistoryModalRow