from playwright.sync_api import sync_playwright
import os

def run():
    with sync_playwright() as p:
        browser = p.chromium.launch()
        page = browser.new_page()

        # Determine absolute path to the HTML file
        file_path = os.path.abspath("verification/mock_tuvi_fix.html")
        page.goto(f"file://{file_path}")

        # Take screenshot
        page.screenshot(path="verification/tuvi_fix_check.png")
        print("Screenshot saved to verification/tuvi_fix_check.png")
        browser.close()

if __name__ == "__main__":
    run()
