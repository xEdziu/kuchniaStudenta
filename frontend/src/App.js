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
import Activate from './pages/Activate';
import Page404 from './pages/404';
import Logout from './pages/Logout';

export default function App() {
  return (
    <>
      <Router>
        <Nav />
        <Routes>
          <Route path="/recipes" element={<Recipes />}></Route>
          <Route path="/login" element={<Login />}></Route>
          <Route path="/register" element={<Register />}></Route>
          <Route path="/activate/:token" element={<Activate />}></Route>
          <Route path="/contact" element={<Contact />}></Route>
          <Route path="/account" element={<Account />}></Route>
          <Route path="/logout" element={<Logout />}></Route>
          <Route path="/" element={<Home />}></Route>
          <Route path="*" element={<Page404 />}></Route>
        </Routes>
        <Footer />
      </Router>
    </>
  );
}