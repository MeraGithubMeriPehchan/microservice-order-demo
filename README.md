# Microservice Order Demo

A demonstration of microservices architecture using Symfony, featuring an order service and payment service that communicate via message queues.

## Quick Recovery Setup

After the git-filter-repo incident, follow these steps to get the project running again:

1. **Install dependencies**
   ```bash
   cd services/order-service && composer install
   cd ../payment-service && composer install
   ```

2. **Set up database**
   ```bash
   cd services/order-service
   php bin/console doctrine:database:create
   php bin/console doctrine:migrations:migrate
   ```

3. **Start services**
   ```bash
   # Terminal 1 - Order Service
   cd services/order-service
   symfony server:start --port=8001 --allow-http --no-tls
   
   # Terminal 2 - Payment Service  
   cd services/payment-service
   symfony server:start --port=8002 --allow-http --no-tls
   
   # Terminal 3 - Message Consumer
   cd services/payment-service
   php bin/console messenger:consume async -vv
   ```

4. **Test the API**
   ```bash
   curl -X POST http://127.0.0.1:8001/orders \
     -H "Content-Type: application/json" \
     -d '{"items":[{"sku":"SKU-123","qty":1,"price":9.99}],"total":9.99}'
   ```

## Architecture

- **Order Service** (Port 8001): Creates orders, sends messages
- **Payment Service** (Port 8002): Processes payments, consumes messages
- **RabbitMQ**: Message queue for async communication

## Key Files Recovered

- ✅ Composer configurations
- ✅ Symfony Kernel and entry points
- ✅ Order entity and controller
- ✅ Message classes with serialization
- ✅ Payment message handler
- ✅ Configuration files (messenger, doctrine, framework)
- ✅ Database migration

The project should now be fully functional again!