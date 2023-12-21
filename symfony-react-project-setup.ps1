# Install Symfony and create a new project
composer create-project symfony/skeleton backend

# Change to the backend directory
Set-Location -Path backend

# Install necessary Symfony packages
composer require symfony/orm-pack
composer require --dev symfony/maker-bundle
composer require symfony/validator
composer require symfony/webpack-encore-bundle
composer require nelmio/cors-bundle
composer require symfony/flex
composer require symfony/runtime

# Initialize a new React.js application
Set-Location -Path ..
npx create-react-app frontend

# Change to the frontend directory
Set-Location -Path frontend

# Install necessary npm packages
npm install --save @testing-library/jest-dom
npm install --save @testing-library/react
npm install --save @testing-library/user-event
npm install --save @types/jest
npm install --save @types/node
npm install --save @types/react
npm install --save @types/react-dom
npm install --save dotenv
npm install --save react
npm install --save react-dom
npm install --save react-icons
npm install --save react-router-dom
npm install --save react-scripts
npm install --save styled-components
npm install --save axios