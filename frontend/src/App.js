import React from 'react';
import { BrowserRouter as Router, Route, Routes } from 'react-router-dom';
import Home from './pages/Home';
import Footer from './components/Footer';
import Nav from './components/Nav';

export default function App() {
  return (
    <>
      <Router>
        <Nav />
        <Routes>
          <Route path="/about">
            {/* <About /> */}
          </Route>
          <Route path="/contact">
            {/* <Contact /> */}
          </Route>
          <Route path="/projects">
            {/* <Projects /> */}
          </Route>
          <Route path="/" element={<Home />}></Route>
        </Routes>
        <Footer />
      </Router>
    </>
  );
}