import React from 'react';
import styled from 'styled-components';

const ContactStyles = styled.div`
    display: flex;
    justify-content: center;
    align-items: center;
    height: 80vh;
    .container {
        width: 50%;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        h1 {
            font-size: 2rem;
            margin-bottom: 2rem;
        }
    }
`;

function Contact() {

    return (
        <ContactStyles>
            <div className="container">
                <h1>Kontakt</h1>
                <p></p>
            </div>
        </ContactStyles>
    );
}
export default Contact;
