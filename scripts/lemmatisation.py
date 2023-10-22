import sys
from nltk.stem.snowball import FrenchStemmer
'''
tab = sys.argv[1].split(",")
newTab = ""
for i in tab:
    newTab += FrenchStemmer().stem(i)+","

print(newTab)
'''

mot = sys.argv[1]
newMot = FrenchStemmer().stem(mot)
print(newMot)
