// ./assets/js/components/Home.js

import React, {Component} from 'react';
import {Route, Switch, Link} from 'react-router-dom';
import SetupCheck from "./SetupCheck";
import ExchangeRates from "./ExchangeRates";
import {format} from "../helpers/date";

const today = new Date()
const options = [{year: 'numeric'}, {month: '2-digit'}, {day: '2-digit'}]

class Home extends Component {
    render() {
        return (
            <div>
                <nav className="navbar navbar-expand-lg navbar-dark bg-dark">
                    <Link className={"navbar-brand"} to={"#"}> Telemedi Zadanko </Link>
                    <div id="navbarText">
                        <ul className="navbar-nav mr-auto">
                            <li className="nav-item d-flex">
                                <Link className={"nav-link"} to={`/exchange-rates/${format(today, options)}`}>
                                    Exchange Rates
                                </Link>
                                <Link className={"nav-link"} to={"/setup-check"}> React Setup Check </Link>
                            </li>

                        </ul>
                    </div>
                </nav>
                <Switch>
                    <Route path="/exchange-rates/:date" component={ExchangeRates}/>
                    <Route path="/setup-check" component={SetupCheck}/>
                </Switch>
            </div>
        )
    }
}

export default Home;
