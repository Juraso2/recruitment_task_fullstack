import React, {useEffect, useState} from 'react'
import {useParams} from "react-router-dom";
import Table from "../components/Table/Table"
import Search from "../components/Search";
import useAxiosWithAbort from "../hooks/useAxiosWithAbort";
import useEventBus from "../hooks/useEventBus";
import HistoryModal from "../components/HistoryModal/HistoryModal";

const baseUrl = 'http://telemedi-zadanie.localhost'

const ExchangeRates = () => {
    const eventBus = useEventBus()
    const {date} = useParams()
    const [prevDate, setPrevDate] = useState(date)
    const [showModal, setShowModal] = useState(false)
    const [historicalDate, setHistoricalDate] = useState(null)
    const [historicalCurrency, setHistoricalCurrency] = useState(null)
    const [{currencies: todayCurrencies}] = useAxiosWithAbort(baseUrl + `/api/exchange-rates`)
    const [{currencies}, islLoading, error, refetch] = useAxiosWithAbort(baseUrl + `/api/exchange-rates/` + date)

    const showHistoricalRates = ({currency, date}) => {
        setHistoricalCurrency(currency)
        setHistoricalDate(date)

        handleShowModal()
    }

    const handleShowModal = () => {
        setShowModal(true)
    }

    let timer = null
    const handleCloseModal = () => {
        setShowModal(false)

        timer = setTimeout(() => {
            setHistoricalCurrency(null)
            setHistoricalDate(null)
            clearTimeout(timer)
        }, 300)
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

        refetch()
        setPrevDate(date)
    }, [date])

    return (
        <section className="row-section">
            <div className="container-fluid px-4 pb-5">
                <div className="row no-gutters mt-5">
                    <div className="col-md-12">
                        <h1 className="text-center">Exchange Rates</h1>
                    </div>

                    <Search label="Selected date" value={date}/>

                    <Table
                        historicalCurrencies={currencies || []}
                        todayCurrencies={todayCurrencies || []}
                        isLoading={islLoading}
                        date={date}
                    />

                    {error && <div className="col-12 alert alert-danger mt-3 px-3" role="alert">{error}</div>}

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