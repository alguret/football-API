## Generate the SSH keys:
```bash
mkdir config/jwt
openssl genrsa -out config/jwt/private.pem -aes256 4096
openssl rsa -pubout -in config/jwt/private.pem -out config/jwt/public.pem
```

## Get started
1. Create database - `php bin/console doctrine:database:create`
2. Apply migrations - `php bin/consol doctrine:migrations:migrate`
3. Fill database with test data - `php bin/console doctrine:fixtures:load`
4. Start HTTP development server - `php bin/console server:run`

## API ENDPOINTS

##### Login user
```
curl -X POST http://localhost:8000/auth/login -H 'content-type: application/json' -d '{"login": "test", "password": "test"}'
```

##### Get a list of football teams in a single league
```bash
curl -X GET 'http://localhost:8000/leagues/$LEAGUE_ID/teams' -H 'Authorization: Bearer $TOKEN'
```

##### Create a football team
```bash
curl -X POST 'http://localhost:8000/leagues/$LEAGUE_ID/teams' -H 'Authorization: Bearer $TOKEN' -H 'content-type: application/json' -d '{"name": "name", "strip": "strip"}'
```

##### Modify all attributes of a football team
```bash
curl -X PUT 'http://localhost:8000/teams/$TEAM_ID' -H 'Authorization: Bearer $TOKEN' -H 'content-type: application/json' -d '{"name": "name", "strip": "strip"}'
```

##### Delete a football league
```bash
curl -X DELETE -H 'Authorization: Bearer $TOKEN' 'http://localhost:8000/leagues/$LEAGUE_ID'
```
