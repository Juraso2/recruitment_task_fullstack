import React from "react";
import Modal from "./Modal/Modal";
import PropTypes from "prop-types";
import useAxiosWithAbort from "../hooks/useAxiosWithAbort";

const baseUrl = 'http://telemedi-zadanie.localhost'

const HistoryModal = ({showModal, handleCloseModal, currency, date}) => {
    const [{currencies: todayCurrencies}] = useAxiosWithAbort(baseUrl + `/api/exchange-rates`)

    if(!currency || !date) return null

    return (
        <Modal show={showModal} onClose={handleCloseModal}>
            <h2>Historical rates for {currency.name} on {date}</h2>
        </Modal>
    )
}

HistoryModal.propTypes = {
    showModal: PropTypes.bool.isRequired,
    handleCloseModal: PropTypes.func.isRequired,
    currency: PropTypes.object,
    date: PropTypes.string,
}

export default HistoryModal