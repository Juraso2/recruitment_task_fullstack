import React, {Fragment} from "react";
import PropTypes from "prop-types";
import Skeleton from "../Skeleton";
import ArrowIndicator from "../ArrowIndicator";
import Tooltip from "../Tooltip";

const TableRow = ({date, currency, todayCurrency}) => {
    return (
        <Fragment>
            <tr>
                <td className="align-middle" align="center">
                    {currency.name}
                </td>

                <td className="align-middle" align="center">
                    <strong>{currency.code}</strong>
                </td>

                <td>
                    <div className="text-muted small">{date}</div>

                    <ArrowIndicator
                        from={currency.exchangeRate || 0}
                        to={todayCurrency && todayCurrency.exchangeRate || 0}
                    />

                    {currency.exchangeRate}
                </td>

                <td>
                    <div className="text-muted small">Today</div>
                    {todayCurrency && todayCurrency.exchangeRate || '-'}
                </td>

                <td>
                    {currency.isPurchasable ?
                        <>
                            <div className="text-muted small">{date}</div>

                            <ArrowIndicator
                                from={currency.purchaseRate || 0}
                                to={todayCurrency && todayCurrency.purchaseRate || 0}
                            />

                            {currency.purchaseRate || '-'}
                        </>
                        : '-'
                    }
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
                            <div className="text-muted small">{date}</div>

                            <ArrowIndicator
                                from={currency.sellRate || 0}
                                to={todayCurrency && todayCurrency.sellRate || 0}
                            />

                            {currency.sellRate || '-'}
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

                <td className="align-middle" align="center">
                    <Tooltip text="Historical rates">
                        <button className="btn btn-primary">
                            <i className="fa fa-history" aria-hidden="true"></i>
                        </button>
                    </Tooltip>
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