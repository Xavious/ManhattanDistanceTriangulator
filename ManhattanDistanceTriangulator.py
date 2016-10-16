import pprint
def GetPingers():
	count = int(input("How many pingers do you have?"))
	pingers = []
	for i in range(0, count):
		pinger = {}
		print("Pinger #" + str(i + 1))
		pinger['x'] = int(input("Enter a X coordinate:"))
		pinger['y'] = int(input("Enter a Y coordinate:"))
		pinger['z'] = int(input("Enter the ping distance:"))
		pingers.append(pinger)
	return pingers
 
def GeneratePingSectors(pingers):
	sector_groups = []
	for pinger in pingers:
		sectors = []
		s = set()
		for i in range(0, pinger['z']):
			s.add((pinger['x'] + pinger['z'] - i, pinger['y'] + i))
			s.add((pinger['x'] - pinger['z'] + i, pinger['y'] - i))
			s.add((pinger['x'] - i, pinger['y'] + pinger['z'] - i))
			s.add((pinger['x'] + i, pinger['y'] - pinger['z'] + i))
		sector_groups.append(s)
	return sector_groups

sector_groups = GeneratePingSectors(GetPingers())
possible_sectors = sector_groups[0]
for s2 in sector_groups[1:]:
    possible_sectors = possible_sectors & s2

print("There are " + str(len(possible_sectors)) + " possible sectors:")
for sector in possible_sectors:
	print(str(sector[0]) + ',' + str(sector[1]))