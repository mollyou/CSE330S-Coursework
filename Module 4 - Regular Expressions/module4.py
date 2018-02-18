#Open file, see if command line argument is present
import sys, os 
if len(sys.argv) < 2:
	sys.exit("Usage: %s filename" % sys.argv[0])
filename = sys.argv[1] 
if not os.path.exists(filename):
	sys.exit("Error: File '%s' not found" % sys.argv[1])
	

#Regular Expressions and functions
import re

def get_name(test):
	name_regex = re.compile(r"\b(\w+ \w+) batted (\d+) times with (\d+) hits and \d+ runs\b")
	match = name_regex.match(test)
	if match is not None:
		return match.group(1)
	else:
		return False 

def get_bats(test):
	bats_regex = re.compile(r"\b(\w+ \w+) batted (\d+) times with (\d+) hits and \d+ runs\b")
	match = bats_regex.match(test)
	if match is not None:
		return match.group(2)
	else:
		return False 

def get_hits(test):
	hits_regex = re.compile(r"\b(\w+ \w+) batted (\d+) times with (\d+) hits and \d+ runs\b")
	match = hits_regex.match(test)
	if match is not None:
		return match.group(3)
	else:
		return False 

#Define Player Object
class Player:
	def __init__(self, name, battingtotal, hittotal):
		self.name = name
		self.battingtotal = int(battingtotal)
		self.hittotal = int(hittotal)
	
	def add_game(self, bats, hits):
		self.battingtotal += int(bats)
		self.hittotal += int(hits)

	def get_avrg(self):
		if self.battingtotal > 0:
			return round(float(self.hittotal/float(self.battingtotal)), 3)
		else:
			return 0
 
 #search
#Open file
f = open(filename)
dict = {}
for line in f:
	playername = get_name(line)
	playerbats = get_bats(line)
	playerhits = get_hits(line)
	#print "%s: %s %s" % (playername, playerbats, playerhits)
	if playername != False and playerbats != False and playerhits != False:
		#if the player is already in the dictionary, update their batting and hits
		if dict.has_key(playername):
			dict[playername].add_game(playerbats, playerhits)
		
		#if the player is not already in the dictionary, add them to the dictionary and add their batting and htis
		else:
			dict[playername] = Player(playername, playerbats, playerhits)

		
#after looking through the whole file, calculate averages
avrgdict = {}
for key in dict:
	avrgdict[key] = dict[key].get_avrg()
			
#print everything
for key, value in reversed(sorted(avrgdict.iteritems(), key=lambda (k,v): (v,k))):
    print "%s: %s" % (key, value)

f.close()