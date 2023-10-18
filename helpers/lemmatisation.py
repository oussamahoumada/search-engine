import sys
from nltk.stem.snowball import FrenchStemmer

php_param = sys.argv[1]
stemmer = FrenchStemmer()
print(stemmer.stem(php_param))
