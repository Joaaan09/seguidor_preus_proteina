from selenium import webdriver
from selenium.webdriver.chrome.service import Service
from selenium.webdriver.common.by import By
from selenium.webdriver.support.ui import WebDriverWait
from selenium.webdriver.support import expected_conditions as EC
from webdriver_manager.chrome import ChromeDriverManager
import json

def get_myprotein_price():
    # Configura el driver amb ChromeDriverManager
    service = Service(ChromeDriverManager().install())
    driver = webdriver.Chrome(service=service)
    
    try:
        driver.get("https://www.myprotein.es/p/nutricion-deportiva/impact-whey-protein/10530943/?variation=10531012")
        
        # Acceptar cookies (si existeix)
        try:
            cookie_btn = WebDriverWait(driver, 5).until(
                EC.element_to_be_clickable((By.ID, "onetrust-accept-btn-handler"))
            )
            cookie_btn.click()
        except Exception as e:
            print(f"No s'ha pogut acceptar cookies: {e}")
        
        # Esperar a que carregui el preu
        price_element = WebDriverWait(driver, 20).until(
            EC.presence_of_element_located((By.CSS_SELECTOR, "span.text-2xl.font-semibold"))
        )
        
        # Netejar dades
        price_text = price_element.text.replace('€', '').replace(',', '.').strip()
        
        return {
            "store": "MyProtein",
            "price": float(price_text),
            "discount": 30  # Exemple: Calcular descompte real comparant amb preu original
        }
    
    except Exception as e:
        print(f"Error durant el scraping: {e}")
        return {
            "store": "MyProtein",
            "error": str(e)  # Retorna l'error com a part del JSON
        }
    
    finally:
        driver.quit()  # Assegura't que el driver es tanqui sempre

if __name__ == "__main__":
    result = get_myprotein_price()
    print(json.dumps(result))  # Imprimeix el resultat com a JSON