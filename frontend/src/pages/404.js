import React from 'react';
import styled from 'styled-components';
import Button from '../components/Button';

const Page404Styles = styled.div`
    display: flex;
    justify-content: center;
    align-items: center;
    height: 75vh;
    .container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        margin-bottom: 15vh;
        #info {
            text-align: center;
            h3 {
                font-size: 2rem;
                margin-bottom: 2rem;
            }
        }
        img {
            width: 30%;
            height: auto;
        }
    }
`;

function Page404() {

    return (
        <Page404Styles>
            <div className="container">
                <img src="https://i.imgur.com/qIufhof.png" alt="cone" />
                <div id="info">
                    <h1>404</h1>
                    <h3>Hmm... tutaj nie ma przepisów!</h3>
                    <Button btnText="Ale tu jest strona główna!" btnLink="/" />
                </div>
            </div>
        </Page404Styles>
    );
}
export default Page404;
