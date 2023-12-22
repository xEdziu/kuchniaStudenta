import React from 'react'
import styled from 'styled-components';

const FooterStyles = styled.div`
    background-color: var(--bg);
    padding: 1rem;
    width: 100%;
    text-align: center;
    color: var(--dark);
`;

export default function Footer() {
    return (
        <FooterStyles>
            <div className="container">
                <p>Adrian Goral & Jakub Przybysz 2023</p>
            </div>
        </FooterStyles>
    )
}