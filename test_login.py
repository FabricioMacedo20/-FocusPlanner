#!/usr/bin/env python3
import requests
from bs4 import BeautifulSoup
import re

# URL base
base_url = "http://127.0.0.1:8000"

# Criar uma sessão para manter cookies
session = requests.Session()

# 1. Acessar a página de login para obter o CSRF token
print("1. Acessando página de login...")
response = session.get(f"{base_url}/login")
print(f"   Status: {response.status_code}")

# 2. Extrair o CSRF token
print("2. Extraindo CSRF token...")
soup = BeautifulSoup(response.text, 'html.parser')
csrf_token = None

# Procurar pelo token em diferentes lugares
# Pode estar em um input hidden ou em uma meta tag
for input_field in soup.find_all('input', {'name': '_token'}):
    csrf_token = input_field.get('value')
    break

if not csrf_token:
    # Procurar em meta tag
    for meta in soup.find_all('meta', {'name': 'csrf-token'}):
        csrf_token = meta.get('content')
        break

if not csrf_token:
    # Procurar com regex
    match = re.search(r'_token["\']?\s*[:=]\s*["\']([^"\']+)', response.text)
    if match:
        csrf_token = match.group(1)

print(f"   CSRF Token: {csrf_token[:20] if csrf_token else 'NÃO ENCONTRADO'}...")

# 3. Fazer login
if csrf_token:
    print("3. Fazendo login...")
    login_data = {
        '_token': csrf_token,
        'email': 'luizfabricio0811@icloud.com',
        'password': 'password'
    }
    
    response = session.post(f"{base_url}/login", data=login_data, allow_redirects=False)
    print(f"   Status: {response.status_code}")
    print(f"   Headers: {dict(response.headers)}")
    
    if response.status_code == 302:
        print("   ✅ Login bem-sucedido! Redirecionando para dashboard...")
        # Seguir redirecionamento
        response = session.get(response.headers['Location'])
        print(f"   Dashboard Status: {response.status_code}")
        if response.status_code == 200:
            print("   ✅ Dashboard carregado com sucesso!")
    else:
        print(f"   ❌ Erro no login: {response.status_code}")
        print(f"   Body: {response.text[:500]}")
else:
    print("   ❌ CSRF token não encontrado!")
