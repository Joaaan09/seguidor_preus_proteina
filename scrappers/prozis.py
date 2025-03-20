from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager
import json

def get_prozis_price():
    # Configura el driver amb ChromeDriverManager
    service = Service(ChromeDriverManager().install())
    driver = webdriver.Chrome(service=service)
    
    try:
        # Obre la pàgina del producte
        driver.get("https://www.prozis.com/es/es/prozis/100-real-whey-protein-1000-g")
        
        # Acceptar cookies (si existeix)
        try:
            cookie_btn = WebDriverWait(driver, 5).until(
                EC.element_to_be_clickable((By.ID, "CybotCookiebotDialogBodyLevelButtonLevelOptinAllowAll"))
            )
            cookie_btn.click()
        except Exception as e:
            print(f"No s'ha pogut acceptar cookies: {e}")
        
        # Esperar a que carregui el preu
        price_element = WebDriverWait(driver, 20).until(
            EC.presence_of_element_located((By.CSS_SELECTOR, "p.final-price"))
        )
        
        # Obtenir el preu de l'atribut data-qa
        price_text = price_element.get_attribute("data-qa").replace('€', '').strip()
        
        # Verificar que el preu no estigui buit
        if not price_text:
            raise ValueError("El preu està buit.")
        
        return {
            "store": "Prozis",
            "price": float(price_text),
            "discount": 25  # Exemple: Calcular descompte real comparant amb preu original
        }
    
    except Exception as e:
        print(f"Error durant el scraping: {e}")
        return {
            "store": "Prozis",
            "error": str(e)  # Retorna l'error com a part del JSON
        }
    
    finally:
        driver.quit()  # Assegura't que el driver es tanqui sempre

if __name__ == "__main__":
    result = get_prozis_price()
    print(json.dumps(result))  # Imprimeix el resultat