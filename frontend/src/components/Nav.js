import React, { useState, useEffect } from 'react'
import styled from 'styled-components';
import logo from '../assets/images/logo-clear-bg.svg';

const NavStyles = styled.div`
    background-color: var(--bg);
    padding: 0 2rem;
    position: sticky;
    top: 0;
    width: 100%;
    z-index: 100;
    .container {
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: 1200px;
        margin: 0 auto;
        height: 100%;
        padding: 0;
    }
    ul {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    li {
        margin-left: 2rem;
    }
    a {
        color: var(--dark);
        font-size: 1.3rem;
        font-weight: 600;
        &:hover {
            color: var(--accent);
        }
    }
    .logo {
        max-width: 150px;
        margin-top: 1rem;
        margin-bottom: 1rem;
    }
    .active {
        color: var(--accent);
    }
    &.scrolled {
        box-shadow: 0 5px 5px rgba(0,0,0,0.1);
    }
    @media only screen and (max-width: 768px) {
        .container {
            flex-direction: column;
        }
        ul {
            flex-direction: column;
            margin-top: 1rem;
        }
        .logo {
            text-align: center;
        }
        li {
            margin: 0.5rem 0;
        }
    }
`;

export default function Nav() {
    const [scrolled, setScrolled] = useState(false);

    useEffect(() => {
        const handleScroll = () => {
            const isScrolled = window.scrollY > 50;
            if (isScrolled !== scrolled) {
                setScrolled(!scrolled);
            }
        };

        document.addEventListener('scroll', handleScroll);
        return () => {
            document.removeEventListener('scroll', handleScroll);
        };
    }, [scrolled]);
    return (
        <NavStyles className={scrolled ? 'scrolled' : ''}>
            <div className="container">
                <img src={logo} alt="logo" className="logo" />
                <ul>
                    <li>
                        <a href="/" className="active">Strona Główna</a>
                    </li>
                    <li>
                        <a href="/about">O nas</a>
                    </li>
                    <li>
                        <a href="/recipes">Przepisy</a>
                    </li>
                    <li>
                        <a href="/login">Logowanie</a>
                    </li>
                </ul>
            </div>
        </NavStyles>
    )
}