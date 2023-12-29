import React, { useEffect, useState } from 'react';
import styled from 'styled-components';
import { useParams } from 'react-router-dom';
import axios from 'axios';

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
        .obj{
            width:50%;
            background-color: white;
            padding: 3rem;
            border-radius: 5px;
            box-shadow: 0 5px 5px rgba(0,0,0,0.1);
            h3 {
                margin: 1rem 0;
                font-size: 1.6rem;
                text-align: center;
            }
        }
    }
    
`;

function Activate() {
    const { token } = useParams();
    const [data, setData] = useState("");
    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await axios.post(`https://${process.env.REACT_APP_SYMFONY}/api/activate/${token}`);
                console.log(response);
                setData(response.data.message);
            } catch (error) {
                console.error('Error fetching data from backend:', error);
            }
        };
        fetchData();
    }, [token]);
    return (
        <ActivateStyles>
            <div className="container">
                <div className='obj'>
                    <h3>{data}</h3>
                </div>
            </div>
        </ActivateStyles>
    );
}
export default Activate;
