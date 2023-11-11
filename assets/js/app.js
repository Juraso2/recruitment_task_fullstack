/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
//import './css/app.css';

// start the Stimulus application
//import './bootstrap';


import React from 'react';
import ReactDOM from 'react-dom';
import {BrowserRouter as Router} from 'react-router-dom';
import '../css/app.css';
import Home from './pages/Home';
import AlertProvider from "./provider/AlertProvider";
import AlertPopup from "./components/AlertPopup";

ReactDOM.render(
    <Router>
        <AlertProvider>
            <AlertPopup/>
            <Home/>
        </AlertProvider>
    </Router>,
    document.getElementById('root')
);

