import React, {Fragment} from "react";
import PropTypes from "prop-types";
import Skeleton from "../Skeleton";

const TableRow = ({date, currency, todayCurrency}) => {
    return (
        <Fragment>
            <tr>
                <td rowSpan="2" className="align-middle" align="center">
                    {currency.name}
                </td>
                <td rowSpan="2" className="align-middle" align="center">
                    <strong>{currency.code}</strong>
                </td>
                <td>
                    <div className="text-muted small">{date}</div>
                    {currency.exchangeRate}
                </td>
                <td>
                    {currency.isPurchasable ?
                        <>
                            <div className="text-muted small">{date}</div>
                            {currency.purchaseRate || '-'}
                        </>
                        : '-'
                    }
                </td>
                <td>
                    {currency.isSellable ?
                        <>
                            <div className="text-muted small">{date}</div>
                            {currency.sellRate || '-'}
                        </>
                        : '-'
                    }
                </td>
            </tr>
            <tr>
                <td>
                    <div className="text-muted small">Today</div>
                    {todayCurrency && todayCurrency.exchangeRate || '-'}
                </td>
                <td>
                    {currency.isPurchasable ?
                        <>
                            <div className="text-muted small">Today</div>
                            {
                                todayCurrency ?
                                    todayCurrency.purchaseRate || '-'
                                    : <Skeleton className="mt-1"/>
                            }
                        </>
                        : '-'
                    }
                </td>
                <td>
                    {currency.isSellable ?
                        <>
                            <div className="text-muted small">Today</div>
                            {
                                todayCurrency ?
                                    todayCurrency.sellRate || '-'
                                    : <Skeleton className="mt-1"/>
                            }
                        </>
                        : '-'
                    }
                </td>
            </tr>
        </Fragment>
    )
}

TableRow.propTypes = {
    date: PropTypes.string.isRequired,
    currency: PropTypes.shape({
        name: PropTypes.string.isRequired,
        code: PropTypes.string.isRequired,
        exchangeRate: PropTypes.number.isRequired,
        isPurchasable: PropTypes.bool.isRequired,
        purchaseRate: PropTypes.number.isRequired,
        isSellable: PropTypes.bool.isRequired,
        sellRate: PropTypes.number.isRequired,
    }).isRequired,
    todayCurrency: PropTypes.shape({
        name: PropTypes.string.isRequired,
        code: PropTypes.string.isRequired,
        exchangeRate: PropTypes.number.isRequired,
        isPurchasable: PropTypes.bool.isRequired,
        purchaseRate: PropTypes.number.isRequired,
        isSellable: PropTypes.bool.isRequired,
        sellRate: PropTypes.number.isRequired,
    }),
}

export default TableRow