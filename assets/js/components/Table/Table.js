import React, {Fragment} from "react"
import Skeleton from "../Skeleton"
import PropTypes from "prop-types"
import TableRow from "./TableRow";

const Table = ({historicalCurrencies, todayCurrencies, isLoading, date}) => {
    return (
        <div className="table-responsive">
            <table className="table table-bordered mb-0 bg-light">
                <thead className="thead-dark">
                <tr>
                    <th>Currency</th>
                    <th>Code</th>
                    <th>NBP Rate</th>
                    <th>Buy Rate</th>
                    <th>Sell Rate</th>
                </tr>
                </thead>

                <tbody>
                {isLoading ? (
                    <>
                        <tr>
                            <td><Skeleton/></td>
                            <td><Skeleton/></td>
                            <td><Skeleton/></td>
                            <td><Skeleton/></td>
                            <td><Skeleton/></td>
                        </tr>
                        <tr>
                            <td><Skeleton/></td>
                            <td><Skeleton/></td>
                            <td><Skeleton/></td>
                            <td><Skeleton/></td>
                            <td><Skeleton/></td>
                        </tr>
                        <tr>
                            <td><Skeleton/></td>
                            <td><Skeleton/></td>
                            <td><Skeleton/></td>
                            <td><Skeleton/></td>
                            <td><Skeleton/></td>
                        </tr>
                    </>
                ) : (
                    <>
                        {historicalCurrencies.map(currency => (
                            <TableRow
                                date={date}
                                currency={currency}
                                todayCurrency={todayCurrencies.find(todayCurrency => todayCurrency.code === currency.code)}
                                key={currency.code}
                            />
                        ))}
                    </>
                )}
                </tbody>
            </table>
        </div>
    )
}

Table.propTypes = {
    historicalCurrencies: PropTypes.arrayOf(
        PropTypes.shape({
            name: PropTypes.string.isRequired,
            code: PropTypes.string.isRequired,
            exchangeRate: PropTypes.number.isRequired,
            isPurchasable: PropTypes.bool.isRequired,
            purchaseRate: PropTypes.number.isRequired,
            isSellable: PropTypes.bool.isRequired,
            sellRate: PropTypes.number.isRequired,
        })
    ),
    todayCurrencies: PropTypes.arrayOf(
        PropTypes.shape({
            name: PropTypes.string.isRequired,
            code: PropTypes.string.isRequired,
            exchangeRate: PropTypes.number.isRequired,
            isPurchasable: PropTypes.bool.isRequired,
            purchaseRate: PropTypes.number.isRequired,
            isSellable: PropTypes.bool.isRequired,
            sellRate: PropTypes.number.isRequired,
        })
    ),
    isLoading: PropTypes.bool.isRequired,
    date: PropTypes.string.isRequired,
}

export default Table
