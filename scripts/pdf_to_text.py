import sys
from pdfminer.high_level import extract_text

print(str(extract_text(sys.argv[1]).encode("utf-8")).replace("\\n", " "))


'''with open("result.txt", "w", encoding="utf-8") as file:
    # Add text to the file
    file.write(extract_text_from_pdf("./file.pdf"))'''
