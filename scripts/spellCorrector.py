import sys
from autocorrect import Speller

spell = Speller(lang="fr")
print(spell(sys.argv[1]))
