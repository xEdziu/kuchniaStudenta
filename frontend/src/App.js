import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import Home from './pages/Home';
import About from './pages/About';
import Recipes from './pages/Recipes';
import Login from './pages/Login';
import Footer from './components/Footer';
import Nav from './components/Nav';

export default function App() {
  return (
    <>
      <Router>
        <Nav />
        <Routes>
          <Route path="/about" element={<About />}></Route>
          <Route path="/recipes" element={<Recipes />}></Route>
          <Route path="/login" element={<Login />}></Route>
          <Route path="/" element={<Home />}></Route>
        </Routes>
        <Footer />
      </Router>
    </>
  );
}