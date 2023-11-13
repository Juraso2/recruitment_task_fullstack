import React from "react";
import PropTypes from "prop-types";
import Modal from "../Modal/Modal";
import Skeleton from "../Skeleton";
import useAxiosWithAbort from "../../hooks/useAxiosWithAbort";
import HistoryModalRow from "./HistoryModalRow";

const baseUrl = 'http://telemedi-zadanie.localhost'

const HistoryModal = ({showModal, handleCloseModal, currency, date}) => {
    if (!currency || !date) return null

    const [currencyHistory, isLoading] = useAxiosWithAbort(baseUrl + `/api/exchange-rates/history/${currency.code}/${date}`)

    return (
        <Modal size="lg" show={showModal} onClose={handleCloseModal}>
            <Modal.Header>
                <h5 className="modal-title">
                    <strong>{currencyHistory.name} ({currencyHistory.code})</strong> - {date}
                </h5>
                <button type="button" className="close" aria-label="Close" onClick={handleCloseModal}>
                    <span aria-hidden="true">&times;</span>
                </button>
            </Modal.Header>

            <Modal.Body>
                <table className="table table-striped">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Exchange Rate</th>
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
                            </tr>
                            <tr>
                                <td><Skeleton/></td>
                                <td><Skeleton/></td>
                                <td><Skeleton/></td>
                                <td><Skeleton/></td>
                            </tr>
                        </>
                    ) : (
                        <>
                            {currencyHistory.history.map((row, i) => (
                                <HistoryModalRow
                                    key={row.date}
                                    history={row}
                                    previousHistory={currencyHistory.history[i + 1] || null}
                                    isLast={i === currencyHistory.history.length - 1}
                                />
                            ))}
                        </>
                    )}
                    </tbody>
                </table>
            </Modal.Body>
        </Modal>
    )
}

HistoryModal.propTypes = {
    showModal: PropTypes.bool.isRequired,
    handleCloseModal: PropTypes.func.isRequired,
    currency: PropTypes.shape({
        name: PropTypes.string.isRequired,
        code: PropTypes.string.isRequired,
        exchangeRate: PropTypes.number.isRequired,
        isPurchasable: PropTypes.bool.isRequired,
        purchaseRate: PropTypes.number.isRequired,
        isSellable: PropTypes.bool.isRequired,
        sellRate: PropTypes.number.isRequired,
        history: PropTypes.arrayOf(
            PropTypes.shape({
                date: PropTypes.string.isRequired,
                exchangeRate: PropTypes.number.isRequired,
                purchaseRate: PropTypes.number.isRequired,
                sellRate: PropTypes.number.isRequired,
                isHighest: PropTypes.bool.isRequired,
                isLowest: PropTypes.bool.isRequired,
            })
        ),
    }),
    date: PropTypes.string,
}

export default HistoryModal