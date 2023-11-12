import React, {useEffect, useState} from 'react'
import Table from "../components/Table/Table"
import Search from "../components/Search";
import useAxiosWithAbort from "../hooks/useAxiosWithAbort";
import {useHistory, useParams} from "react-router-dom";

const baseUrl = 'http://telemedi-zadanie.localhost'

const ExchangeRates = () => {
    const history = useHistory()
    const {date} = useParams()
    const [prevDate, setPrevDate] = useState(date)
    const [{currencies: todayCurrencies}] = useAxiosWithAbort(baseUrl + `/api/exchange-rates`)
    const [
        {currencies: historicalCurrencies},
        historicalLoading,
        historicalError,
        historicalRefetch
    ] = useAxiosWithAbort(baseUrl + `/api/exchange-rates/` + date)


    const handleDateChange = (date) => {
        history.push('/exchange-rates/' + date)
    }

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
                </div>
            </div>
        </section>
    )
}

export default ExchangeRates