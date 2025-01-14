from googletrans import Translator

def translate_text_to_hausa(message):
    try:
        translator = Translator()
        translated = translator.translate(message, src='en', dest='ha')
        return translated.text
    except Exception as e:
        return f"Error: {e}"

if __name__ == "__main__":
    import sys
   
    message = sys.argv[1] if len(sys.argv) > 1 else ""
  
    translated_text = translate_text_to_hausa(message)
    
    print(translated_text)
