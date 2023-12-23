import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import Footer from './components/Footer';
import Nav from './components/Nav';
import Home from './pages/Home';
import Recipes from './pages/Recipes';
import Login from './pages/Login';
import Register from './pages/Register';
import Account from './pages/Account';
import Contact from './pages/Contact';

export default function App() {
  return (
    <>
      <Router>
        <Nav />
        <Routes>
          <Route path="/recipes" element={<Recipes />}></Route>
          <Route path="/login" element={<Login />}></Route>
          <Route path="/register" element={<Register />}></Route>
          <Route path="/contact" element={<Contact />}></Route>
          <Route path="/account" element={<Account />}></Route>
          <Route path="/" exact element={<Home />}></Route>
        </Routes>
        <Footer />
      </Router>
    </>
  );
}