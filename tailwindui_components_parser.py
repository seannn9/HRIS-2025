# FOr converting premium tailwind ui components lol

import requests
import re
from collections import defaultdict
from bs4 import BeautifulSoup

def parse_css(url):
    """Parse a CSS file into a dictionary mapping line numbers to selectors."""
    try:
        response = requests.get(url)
        response.raise_for_status()
    except requests.RequestException as e:
        raise ValueError(f"Failed to download CSS from {url}: {e}")

    lines = response.text.splitlines()
    line_selectors = defaultdict(list)

    for line_number, line in enumerate(lines, start=1):
        line = line.strip()
        if not line:
            continue

        # Split into selector part and declarations
        parts = line.split('{', 1)
        if len(parts) < 2:
            continue

        selector_part = parts[0].strip()
        selectors = [s.strip() for s in selector_part.split(',')]

        # Handle class selectors
        for selector in selectors:
            if selector.startswith('.'):
                # Extract class name (including chained classes, but excluding pseudo-classes)
                match = re.match(r'^\.((?:[\w-]|\\.)+)', selector)
                if match:
                    class_name = match.group(1).replace('\\', '')
                    line_selectors[line_number].append(('class', class_name))
            elif selector.startswith('#'):
                # Extract ID name (excluding pseudo-classes and pseudo-elements)
                match = re.match(r'^#((?:[\w-]|\\.)+)', selector)
                if match:
                    id_name = match.group(1).replace('\\', '')
                    line_selectors[line_number].append(('id', id_name))

    return line_selectors

def main():
    # User inputs
    # non_obfuscated_url = input("Enter non-obfuscated CSS URL: ").strip()
    # obfuscated_url = input("Enter obfuscated CSS URL: ").strip()
    html_input = input("Enter HTML code: ").strip()

    non_obfuscated_url = 'https://tailwindui.com/plus-assets/build/iframe/components.css'
    obfuscated_url = 'https://tailwindui.com/plus-assets/build/iframe/compiled.css'

    # Parse CSS files
    try:
        obf_line_selectors = parse_css(obfuscated_url)
        non_obf_line_selectors = parse_css(non_obfuscated_url)
    except ValueError as e:
        print(e)
        return

    # Build mappings for classes and IDs
    class_mapping = {'bjm': 'group'} # bjm is not a defined class in the CSS file so we hardcode it
    id_mapping = {}

    for line_num, obf_selectors in obf_line_selectors.items():
        non_obf_selectors = non_obf_line_selectors.get(line_num, [])
        min_length = min(len(obf_selectors), len(non_obf_selectors))
        for i in range(min_length):
            obf_type, obf_name = obf_selectors[i]
            non_obf_type, non_obf_name = non_obf_selectors[i]

            if obf_type == non_obf_type:
                if obf_type == 'class':
                    class_mapping[obf_name] = non_obf_name
                elif obf_type == 'id':
                    id_mapping[obf_name] = non_obf_name

    # Parse and modify HTML
    soup = BeautifulSoup(html_input, 'html.parser')

    for element in soup.find_all(True):
        # Replace classes
        if 'class' in element.attrs:
            new_classes = [class_mapping.get(cls, cls) for cls in element['class']]
            element['class'] = new_classes

        # Replace IDs
        if 'id' in element.attrs:
            element_id = element['id']
            new_id = id_mapping.get(element_id, element_id)
            element['id'] = new_id

    # Output the modified HTML
    print('\n\nOutput:')
    print(str(soup.prettify()))

if __name__ == "__main__":
    main()