package pl.wedthesaurus;

import java.io.BufferedReader;
import java.io.FileReader;
import java.sql.ResultSet;

public class WriteToDB
{
	public void write(String fileName) throws Exception
	{
		BufferedReader br = new BufferedReader(new FileReader(fileName));
		String sCurrentLine;
		while ((sCurrentLine = br.readLine()) != null)
		{
			System.out.println(sCurrentLine);
			updateDB(sCurrentLine);
		}
		br.close();
		System.out.println("WriteToDB Done"); // only for debug
	}

	private void updateDB(String line) throws Exception
	{
		String[] firstSplit = line.split(":");
		String mainWord = firstSplit[0];
		String otherWords = firstSplit[1];
		int mainLiteralId = getLiteralId(mainWord);
		int mainWordId = getWordId(mainLiteralId);
		MyConnection connection = new MyConnection();
		String[] synonyms = otherWords.split(",");
		for (String sysnonymEntry : synonyms)
		{
			String[] sysnonymSplitEntry = sysnonymEntry.split("\\(");
			String synonym = sysnonymSplitEntry[0];
			String quality = sysnonymSplitEntry[1].substring(
					0, sysnonymSplitEntry[1].length()-1);
			int lteralId = getLiteralId(synonym);
			connection.executeUpdate("INSERT INTO synonyms VALUES ("+mainWordId+", "+lteralId+", "+quality+");");
		}
		connection.close();
	}

	private int getWordId(int literalId) throws Exception
	{
		MyConnection connection = new MyConnection();
		ResultSet wordIdResult = connection.executeQuery(
				"select id from words where literal_id="+literalId+";");
		int wordId = 0;
		if (wordIdResult.next())
		{
			wordId = wordIdResult.getInt("id");
			wordIdResult.close();
		}
		else
		{
			connection.executeUpdate("INSERT INTO words VALUES (null, "+literalId+");");
			ResultSet mainWordIdSecondResult = connection.executeQuery(
					"select id from words where literal_id="+literalId+";");
			if (mainWordIdSecondResult.next())
			{
				wordId = mainWordIdSecondResult.getInt("id");
				mainWordIdSecondResult.close();
			}
		}
		connection.close();
		System.out.println("WordId="+wordId); // only for debug
		return wordId;
	}

	private int getLiteralId(String literal) throws Exception
	{
		MyConnection connection = new MyConnection();
		ResultSet wordIdResult = connection.executeQuery(
				"select id from literals where literal='"+literal+"';");
		int wordId = 0;
		if (wordIdResult.next())
		{
			wordId = wordIdResult.getInt("id");
			wordIdResult.close();
		}
		else
		{
			connection.executeUpdate("INSERT INTO literals VALUES (null, '"+literal+"');");
			ResultSet mainWordIdSecondResult = connection.executeQuery(
					"select id from literals where literal='"+literal+"';");
			if (mainWordIdSecondResult.next())
			{
				wordId = mainWordIdSecondResult.getInt("id");
				mainWordIdSecondResult.close();
			}
		}
		connection.close();
		System.out.println("Literal="+literal+", LiteralId="+wordId); // only for debug
		return wordId;
	}
}
