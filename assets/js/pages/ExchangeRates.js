import React, {useEffect, useState} from 'react'
import Table from "../components/Table/Table"
import Search from "../components/Search";
import useAxiosWithAbort from "../hooks/useAxiosWithAbort";
import {useHistory, useParams} from "react-router-dom";
import useEventBus from "../hooks/useEventBus";
import Modal from "../components/Modal/Modal";
import HistoryModal from "../components/HistoryModal";

const baseUrl = 'http://telemedi-zadanie.localhost'

const ExchangeRates = () => {
    const eventBus = useEventBus()
    const history = useHistory()
    const {date} = useParams()
    const [prevDate, setPrevDate] = useState(date)
    const [showModal, setShowModal] = useState(false)
    const [historicalDate, setHistoricalDate] = useState(null)
    const [historicalCurrency, setHistoricalCurrency] = useState(null)
    const [{currencies: todayCurrencies}] = useAxiosWithAbort(baseUrl + `/api/exchange-rates`)
    const [{currencies: historicalCurrencies}, historicalLoading, historicalError, historicalRefetch] = useAxiosWithAbort(baseUrl + `/api/exchange-rates/` + date)

    const showHistoricalRates = ({currency, date}) => {
        setHistoricalCurrency(currency)
        setHistoricalDate(date)

        handleShowModal()
    }

    const handleShowModal = () => {
        setShowModal(true)
    }

    const handleCloseModal = () => {
        setHistoricalCurrency(null)
        setHistoricalDate(null)

        setShowModal(false)
    }

    const handleDateChange = (date) => {
        history.push('/exchange-rates/' + date)
    }

    useEffect(() => {
        eventBus.on('modalClosed', handleCloseModal)
        eventBus.on('showHistoricalRates', showHistoricalRates)

        return () => {
            eventBus.off('modalClosed', handleCloseModal)
            eventBus.off('showHistoricalRates', showHistoricalRates)
        }
    }, [])

    useEffect(() => {
        if (prevDate === date) {
            return
        }

        historicalRefetch()
        setPrevDate(date)
    }, [date])

    return (
        <section className="row-section">
            <div className="container-fluid px-4 pb-5">
                <div className="row no-gutters mt-5">
                    <div className="col-md-12">
                        <h1 className="text-center">Exchange Rates</h1>
                    </div>

                    <Search
                        label="Date"
                        value={date}
                        onChange={handleDateChange}
                    />

                    <Table
                        historicalCurrencies={historicalCurrencies || []}
                        todayCurrencies={todayCurrencies || []}
                        isLoading={historicalLoading}
                        date={date}
                    />

                    {historicalError &&
                        <div className="col-12 alert alert-danger mt-3 px-3" role="alert">{historicalError}</div>}

                    <HistoryModal
                        showModal={showModal}
                        date={historicalDate}
                        currency={historicalCurrency}
                        handleCloseModal={handleCloseModal}
                    />
                </div>
            </div>
        </section>
    )
}

export default ExchangeRates