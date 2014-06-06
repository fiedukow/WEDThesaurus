#!/usr/local/bin/python

import re
import sys
from collections import defaultdict

mainWordConnection = []

mainWordConnection.append('ncmod')
mainWordConnection.append('dobj')
mainWordConnection.append('ncsubj')
mainWordConnection.append('xcomp')

def splitToWords(wordNumberType):
	floorSplit = wordNumberType.split('_')
	if len(floorSplit) > 1:
		colonSplit = floorSplit[0].split(':')
		if len(colonSplit) > 1:
			return (colonSplit[0].split('+')[0].lower(),floorSplit[1])
		else:
			return ('errPars','000')
	else:
		return ('errPars','000')

def parseSentence(sentence):
	
	wordPart = []
	regEx = ur'\|(.+?)\|'
	words = re.findall(regEx, sentence[0])
	if len(words) > 0:
		del words[-1]
		
		for word in words:
			wordPart.append(splitToWords(word))
		
	
		nouns = []
	
		for onewp in wordPart:
			if onewp[1] == 'NN1' or onewp[1] == 'NN2':
				nouns.append(onewp[0])
	
		del sentence[0]
		del sentence[0]
		ruleList = []
		for line in sentence:
			parts = re.findall(regEx, line)
			isNoun = False
	
			if parts[0] in mainWordConnection:
				tmpList = []
				tmpList.append(parts[0])
				for part in parts[1:]:
					tmpInfo = splitToWords(part)
					tmpList.append(tmpInfo)
					if tmpInfo[1] == 'NN1' or tmpInfo[1] == 'NN2':				
						isNoun = True
				if isNoun:
					ruleList.append(tmpList)
		return (wordPart, ruleList)
				
				
def findDescription(mainWord, wordsDescription, makeList, allWordsDesc):		
	mainLower = mainWord.lower()
	for ps in parsedSentences:

		wordAppear = False
		for word in ps[0]:
			
			
			if mainLower == word[0].lower():
				wordAppear = True

			if makeList and  (word[1] == 'NN1' or word[1] == 'NN2'):
				allWordsDesc[word[0]] = []
		if wordAppear:
			
			for rule in ps[1]:
				
				
				firstWord = ''
				secondWord = ''
				thirdWord = ''
				
				for ruleWord in rule[1:]:
					
					if firstWord == '':
						firstWord = ruleWord[0]
					elif secondWord == '':
						secondWord = ruleWord[0]
					elif thirdWord == '':
						thirdWord = ruleWord[0]

				if thirdWord != mainLower and thirdWord != '' and thirdWord != 'errPars':
					wordsDescription[mainLower].append(thirdWord)
				if secondWord != mainLower and secondWord != '' and secondWord != 'errPars':
					wordsDescription[mainLower].append(secondWord)
				if firstWord != mainLower and firstWord != '' and firstWord != 'errPars':
					wordsDescription[mainLower].append(firstWord)
						
					#if ruleWord[0] != (mainWord).lower():
					#	description.append(ruleWord[0])
					#	#print ruleWord[0]
								
								
						
							
def countDescriptionWords(descriptionList):					
	for key, description in descriptionList.iteritems():
	
		description2 = []
		lastWord = ''
		counter = 0
		for word in description:
			if lastWord == '':
				lastWord = word
				counter += 1
				continue
			elif word == lastWord:
				counter += 1
			else:
				description2.append((counter,lastWord))
				counter = 1
				lastWord = word
		descriptionList[key] = description2



raspFile = open('output.txt','r')
mainWordsFile = open(sys.argv[1],'r')

mainWordList = []
mainWordsDescription = defaultdict(list)

allWordsDescription = defaultdict(list)
allWordList = []

synonymList = defaultdict(list)

for line in mainWordsFile:
	tempStr = line.rstrip('\n')
	mainWordList.append(tempStr)
	mainWordsDescription[tempStr] = []
	synonymList[tempStr] = []	



emptyLine = 0
sentence = []

parsedSentences = []

for line in raspFile:
	if (line == '\n') and (emptyLine == 0):
		emptyLine = 1
		continue
	elif (line == '\n') and (emptyLine == 1):
		parsedSentences.append(parseSentence(sentence))
		sentence=[]
		emptyLine = 0
		continue
	sentence.append(line.rstrip('\n'))





for mainWord in mainWordList:
	findDescription(mainWord, mainWordsDescription, True, allWordsDescription)							
						
							

		
#print ''
for key, description in mainWordsDescription.iteritems():
	mainWordsDescription[key].sort()
#	print key + ': ' + str(description)
#	print ''

#print ''
for key, description in allWordsDescription.iteritems():
	allWordList.append(key)	
	

for allWord in allWordList:
	findDescription(allWord, allWordsDescription, False, allWordsDescription)


#print ''

for key, description in allWordsDescription.iteritems():
	allWordsDescription[key].sort()
#	print key + ': ' + str(description)
#	print ''

#print counter



countDescriptionWords(mainWordsDescription)
countDescriptionWords(allWordsDescription)

#for key, description in mainWordsDescription.iteritems():
#	print key + ': ' + str(description)

#for key, description in allWordsDescription.iteritems():
#	print key + ': ' + str(description)

tmpSynonym = []

for mainKey, mainDescription in mainWordsDescription.iteritems():
	tmpSynonym = []
	for allKey, allDescription in allWordsDescription.iteritems():
		
		value = 0
		if allKey == mainKey:
			continue
		for mainWordDesc in mainDescription:
			for allWordDesc in allDescription:
				if mainWordDesc[1] == allWordDesc[1]:
					value += mainWordDesc[0] * allWordDesc[0]
		tmpSynonym.append((value,allKey))			
	tmpSynonym.sort(reverse=True)
	for synonym in tmpSynonym[:3]:	
		synonymList[mainKey].append(synonym)

synonymFile = open('synonym.output','w')
synonymFile.write('')
synonymFile.close()
synonymFile = open('synonym.output','a')

for key, description in synonymList.iteritems():
	line = str(key)+':'
	for desc in description:
		line+=str(desc[1])+'('+str(desc[0])+'),'
	synonymFile.write(line[:len(line)-1]+'\n')

synonymFile.close()















