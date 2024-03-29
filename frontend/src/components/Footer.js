import React from 'react'
import styled from 'styled-components';

const FooterStyles = styled.div`
    .heart {
        color: red;
    }
    footer {
    /* position: absolute; */
        bottom: 0;
        left: 0;
        right: 0;
        background: #111;
        height: auto;
        padding-top: 40px;
        color: #fff;
    }
    .footer-content {
        display: flex;
        align-items: center;
        justify-content: center;
        flex-direction: column;
        text-align: center;
    }
    .footer-content h3 {
        font-size: 2.1rem;
        font-weight: 500;
        text-transform: capitalize;
        line-height: 3rem;
    }
    .footer-content p {
        max-width: 500px;
        margin: 10px auto;
        margin-bottom: 2rem;
        line-height: 28px;
        font-size: 14px;
        color: #cacdd2;
    }

    .footer-bottom {
        background: #000;
        padding: 20px;
        font-size: 16px;
        padding-bottom: 40px;
        text-align: center;
    }
    .footer-bottom p {
        float: left;
        font-size: 13px;
        word-spacing: 2px;
        text-transform: capitalize;
    }
    .footer-bottom p a {
        color: var(--accent);
        font-size: 15px;
        text-decoration: none;
    }
    .footer-bottom span {
        text-transform: uppercase;
        opacity: 0.4;
        font-weight: 200;
    }
    .footer-menu {
        float: right;
    }
    .footer-menu ul {
        display: flex;
    }
    .footer-menu ul li {
        padding-right: 10px;
        display: block;
    }
    .footer-menu ul li a {
        color: var(--bg);
        text-decoration: none;
    }
    .footer-menu ul li a:hover {
        color: var(--accent);
        
    }

    @media (max-width: 500px) {
        .footer-menu ul {
            display: flex;
            margin-top: 10px;
            margin-bottom: 20px;
        }
    }

`;

export default function Footer() {
    return (
        <FooterStyles>
            <footer>
                <div className="footer-content">
                    <h3>Kuchnia Studenta</h3>
                    <p>
                        Made with <span className="heart">&#10084;</span> by Adrian and Kuba
                    </p>
                </div>
                <div className="footer-bottom">
                    <p>
                        copyright &copy; <a href="/">Kuchnia Studenta</a>{" "}
                    </p>
                    <div className="footer-menu">
                        <ul className="f-menu">
                            <li>
                                <a href="/">Strona Główna</a>
                            </li>
                            <li>
                                <a href="/recipes">Przepisy</a>
                            </li>
                            <li>
                                <a href="/contact">Kontakt</a>
                            </li>
                            <li>
                                <a href="/login">Logowanie</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </footer>
        </FooterStyles>
    )
}