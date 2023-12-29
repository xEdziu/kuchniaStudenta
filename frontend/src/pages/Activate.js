import React from 'react';
import styled from 'styled-components';

const ActivateStyles = styled.div`
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

function Activate() {

    return (
        <ActivateStyles>
            <div className="container">
                <h1>Kontakt</h1>
                <p></p>
            </div>
        </ActivateStyles>
    );
}
export default Activate;
